<?php
/*******************************************************************************
 * Name: Notification pusher / Microsoft adapter
 * Version: 1.0
 * Author: Przemyslaw Ankowski (przemyslaw.ankowski@gmail.com)
 ******************************************************************************/


// Default namespace
namespace Sly\NotificationPusher\Adapter;


/**
 * Class Microsoft
 *
 * @package Sly\NotificationPusher\Adapter
 */
class Microsoft extends \Sly\NotificationPusher\Adapter\BaseAdapter implements \Sly\NotificationPusher\Adapter\AdapterInterface
{
    /**
     * Push
     *
     * @param \Sly\NotificationPusher\Model\PushInterface $Push Push
     * @return \Sly\NotificationPusher\Collection\DeviceCollection
     * @throws \Sly\NotificationPusher\PushException
     */
    public function push(\Sly\NotificationPusher\Model\PushInterface $Push) {
        // Create microsoft client
        $Client = new \Sly\NotificationPusher\Client\Microsoft();

        // Create pushed devices collection
        $PushedDevices = new \Sly\NotificationPusher\Collection\DeviceCollection();

        // Try to send to each client
        foreach ($Push->getDevices() as $Device) {
            // Get message
            $Message = $Push->getMessage();

            // Try to send
            try {
                $this->response = $Client->send($Device->getToken(), $Message->getText(), $Message->getOption("custom"));
            }

            // Something goes wrong
            catch (\Sly\NotificationPusher\Exception\RuntimeException $Exception) {
                throw new \Sly\NotificationPusher\Exception\PushException($Exception->getMessage());
            }

            // Add device to pushed devices list
            $PushedDevices->add($Device);
        }

        // Return pushed devices list
        return $PushedDevices;
    }

    /**
     * Supports
     *
     * @param string $token Token
     * @return boolean
     */
    public function supports($token) {
        return \strlen($token) > 0;
    }

    /**
     * Get default parameters
     *
     * @return array
     */
    public function getDefaultParameters() {
        return array();
    }

    /**
     * Get required parameters
     *
     * @return array
     */
    public function getRequiredParameters() {
        return array();
    }
}
