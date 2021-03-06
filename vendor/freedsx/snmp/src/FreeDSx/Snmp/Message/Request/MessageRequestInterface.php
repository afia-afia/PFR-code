<?php
/**
 * This file is part of the FreeDSx SNMP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Snmp\Message\Request;

use FreeDSx\Snmp\Message\Pdu;
use FreeDSx\Socket\PduInterface;

/**
 * Interface for an SNMP message used in a request.
 *
 *  @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
interface MessageRequestInterface extends PduInterface
{
    /**
     * @return int
     */
    public function getVersion() : int;

    /**
     * @return Pdu
     */
    public function getRequest() : Pdu;

    /**
     * @param Pdu $request
     * @return $this
     */
    public function setRequest(Pdu $request);
}
