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
 * @since         3.0.0
 */
namespace App\Test\Factory;

use App\Model\Table\PermissionsTable;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * PermissionFactory
 */
class PermissionFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Permissions';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'aro' => $faker->text(30),
                'type' => rand(),
                'aco_foreign_key' => $faker->uuid(),
                'created' => Chronos::now(),
                'modified' => Chronos::now(),
            ];
        });
    }

    /**
     * @param string $aco
     * @return PermissionFactory
     */
    public function withAco(string $aco)
    {
        return $this->with('Permissions', compact('aco'));
    }

    /**
     * @return PermissionFactory
     */
    public function withAcoResource()
    {
        return $this->withAco(PermissionsTable::RESOURCE_ACO);
    }
}
