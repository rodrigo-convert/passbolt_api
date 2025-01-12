<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Model\Traits\Cleanup;

use Cake\ORM\Query;
use Cake\Utility\Hash;

trait TableCleanupTrait
{
    /**
     * Delete all association records where associated model entities are soft deleted
     *
     * @param string $association association
     * @param bool|null $dryRun false
     * @param \Cake\ORM\Query|null $query custom query to replace the default find if any
     * @return int Number of affected records
     */
    public function cleanupSoftDeleted(string $association, ?bool $dryRun = false, ?Query $query = null): int
    {
        if (!isset($query)) {
            $query = $this->query()
                ->select(['id'])
                ->leftJoinWith($association)
                ->where([$this->getModelNameFromAssociation($association) . '.deleted' => true]);
        }
        $records = Hash::extract($query->toArray(), '{n}.id');
        if ($dryRun) {
            return count($records);
        }
        if (count($records)) {
            return $this->deleteAll(['id IN' => $records]);
        }

        return 0;
    }

    /**
     * Delete all association records where associated model entities are deleted
     *
     * @param string $association association
     * @param bool|null $dryRun false
     * @param \Cake\ORM\Query|null $query custom query to replace the default find if any
     * @return int Number of affected records
     */
    public function cleanupHardDeleted(string $association, ?bool $dryRun = false, ?Query $query = null): int
    {
        if (!isset($query)) {
            $query = $this->query()
                ->select(['id'])
                ->leftJoinWith($association)
                ->where(function ($exp, $q) use ($association) {
                    return $exp->isNull($this->getModelNameFromAssociation($association) . '.id');
                });
        }
        $records = Hash::extract($query->toArray(), '{n}.id');
        if ($dryRun) {
            return count($records);
        }
        if (count($records)) {
            return $this->deleteAll(['id IN' => $records]);
        }

        return 0;
    }

    /**
     * Extracts the string after the last dot.
     *
     * @param string $association Association path
     * @return string
     */
    protected function getModelNameFromAssociation(string $association): string
    {
        $pos = strrpos($association, '.');

        return $pos === false ? $association : substr($association, $pos + 1);
    }
}
