<?php
/**
 * @package      Virtualcurrency
 * @subpackage   Accounts
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2017 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Virtualcurrency\Account;

use Prism\Domain;
use Prism\Domain\Entity;
use Virtualcurrency\Account\Gateway\AccountGateway;

/**
 * This class provides functionality that manage the persistence of the currency objects.
 *
 * @package      Virtualcurrency
 * @subpackage   Accounts
 */
class Mapper extends Domain\Mapper
{
    /**
     * @var AccountGateway
     */
    protected $gateway;

    /**
     * Initialize the object.
     *
     * <code>
     * $gateway = new Virtualcurrency\Account\Gateway\JoomlaGateway(\JFactory::getDbo());
     * $mapper  = new Virtualcurrency\Account\Mapper($gateway);
     * </code>
     *
     * @param AccountGateway $gateway
     */
    public function __construct(AccountGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Return a gateway object.
     *
     * <code>
     * $gateway = new Virtualcurrency\Account\Gateway\JoomlaGateway(\JFactory::getDbo());
     * $mapper  = new Virtualcurrency\Account\Mapper($gateway);
     *
     * $gateway = $mapper->getGateway();
     * </code>
     *
     * @return AccountGateway
     */
    public function getGateway()
    {
        return $this->gateway;
    }
    
    /**
     * Populate an object.
     *
     * <code>
     * $currencyId = 1;
     *
     * $gateway  = new Virtualcurrency\Account\Gateway\JoomlaGateway(\JFactory::getDbo());
     * $data     = $gateway->fetchById($currencyId);
     *
     * $mapper   = new Virtualcurrency\Account\Mapper($gateway);
     * $currency = $mapper->populate(new Virtualcurrency\Account\Account, $data);
     * </code>
     *
     * @param Entity $object
     * @param array  $data
     *
     * @return Entity
     */
    public function populate(Entity $object, array $data)
    {
        $currencyData = array();
        foreach ($data as $columnName => $value) {
            if (strpos($columnName, 'c_') === 0) {
                $key = str_replace('c_', '', $columnName);
                $currencyData[$key] = $value;
                unset($data[$columnName]);
            }
        }

        $data['currency'] = $currencyData;

        $object->bind($data);

        return $object;
    }

    protected function createObject()
    {
        return new Account;
    }

    protected function insertObject(Entity $object)
    {
        $this->gateway->insertObject($object);
    }

    protected function updateObject(Entity $object)
    {
        $this->gateway->updateObject($object);
    }

    protected function deleteObject(Entity $object)
    {
        // @todo Do deleteObject method in the currency mapper.
    }
}
