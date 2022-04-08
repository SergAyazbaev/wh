<?php

namespace console;

use frontend\models\Dialog_transfer;
use frontend\models\Dialogmessage;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


/**
 *
 */
class Chat implements MessageComponentInterface
{
    protected $clients;
    protected $dialog_id;

    public function __construct()
    {
        echo "start WH...\n";
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');


        ##################
        $array_msg = json_decode($msg);

        $dialod_model = new Dialogmessage();
        $dialod_model->scenario = Dialogmessage::SCENARIO_CREATE;
        $dialod_model->id = Dialogmessage::setNextMaxId();
        $dialod_model->dialog_id = (int)$array_msg->dialog;

        //
        // Сервисное сообщение не пишем в базу
        //
        if ($array_msg->name > -1) {

            $dialod_model->text = $array_msg->msg;  //$msg;
            //$dialod_model->sender_id = (int)$from->resourceId;   // => 173
            $dialod_model->user_id = (int)$array_msg->userid;  //userid;
            $dialod_model->user_name = $array_msg->username;  //userid;

            $dialod_model->date_create = strtotime('now');
            $dialod_model->status = Dialogmessage::STATUS_UNREAD;
            //ddd($dialod_model);

            ///
            if (!$dialod_model->save(true)) {
                ddd($dialod_model->errors);
            }
        }

        //
        // Сервисное сообщение пишем в transfer
        //
        ///
        $mod = new Dialog_transfer();
        $mod->scenario = Dialog_transfer::SCENARIO_CREATE;
        if ($array_msg->name == -1) {
            $mod->scenario = Dialog_transfer::SCENARIO_SYSTEM_MESAGE;
            $mod->status = Dialog_transfer::STATUS_SYSTEM_ONLINE;
        }
        if ($array_msg->name == -2) {
            $mod->scenario = Dialog_transfer::SCENARIO_SYSTEM_MESAGE;
            $mod->status = Dialog_transfer::STATUS_SYSTEM_OFFLINE;
        }
        $mod->dialog_id = $dialod_model->dialog_id;
        $mod->message_id = $dialod_model->id;

        //
        if (!$mod->save(true)) {
            ddd($mod->errors);
        }

        //ddd($dialod_model);
        ##################

        ///
        ///
        foreach ($this->clients as $client) {
            //if ($from == $client) {
            // The sender is not the receiver, send to each client connected
            //$client->send($msg);
            //}

            $client->send($msg);
        }
    }


    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }


    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    /**
     * @return mixed
     */
    public function getDialogId()
    {
        return $this->dialog_id;
    }

    /**
     * @param mixed $dialog_id
     */
    public function setDialogId($dialog_id)
    {
        $this->dialog_id = $dialog_id;
    }
}


/**
 * @param null $var
 */
function ddd($var = null)
{
    if (!isset($var)) {
        echo ' --- пустое значение на входе';
        die();
    }

    if (is_array($var)) {
        echo 'ddd(is_array()) - массив <br>';
    }
    if (is_object($var)) {
        echo 'ddd(is_object()) - объект <br>';
    }
    if (is_bool($var)) {
        echo 'ddd(is_bool()) <br>';
    }

    echo "\n";
    print_r($var);
    echo "\n";
    echo "\n";
    die();
}
