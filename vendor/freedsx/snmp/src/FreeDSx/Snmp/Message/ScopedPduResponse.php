<?php
/**
 * This file is part of the FreeDSx SNMP package.
 *
 * (c) Chad Sikorra <Chad.Sikorra@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FreeDSx\Snmp\Message;

use FreeDSx\Asn1\Type\AbstractType;
use FreeDSx\Snmp\Protocol\Factory\ResponseFactory;

/**
 * Represents a Scoped PDU response.
 *
 * @author Chad Sikorra <Chad.Sikorra@gmail.com>
 */
class ScopedPduResponse extends ScopedPdu
{
    /**
     * @param Pdu $response
     * @param null|EngineId $contextEngineId
     * @param string $contextName
     */
    public function __construct(
        Pdu $response,
        ?EngineId $contextEngineId = null,
        string $contextName = ''
    ) {
        parent::__construct($response, $contextEngineId, $contextName);
    }

    /**
     * @return Pdu
     */
    public function getResponse() : Pdu
    {
        return $this->pdu;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromAsn1(AbstractType $type)
    {
        list($engineId, $contextName, $pdu) = self::parseBaseElements($type);

        return new self(
            ResponseFactory::get($pdu),
            $engineId,
            $contextName
        );
    }
}
