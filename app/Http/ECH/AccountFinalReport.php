<?php


namespace App\Http\ECH;


class AccountFinalReport
{
    private $keyName;
    private $account;
    private $balanceOperator;
    private $set;

    public function setValue($keyName, $account, $balanceOperator = "+")
    {
        $this->keyName = $keyName;
        $this->balanceOperator = $balanceOperator;
        $this->account = $account;
    }


    public function getAccountBalance($data, $balance)
    {
        $data[$this->keyName][$this->account->id]['account_type_id'] =  $this->account->id;
        $data[$this->keyName][$this->account->id]['account_type_name'] = $this->account->display_name;

        if($this->balanceOperator == "+")
        {
            $data[$this->keyName][$this->account->id]['balance'] = $balance;
        } else {
            $data[$this->keyName][$this->account->id]['balance'] = (-1)*$balance;
        }

        return $data;
    }


    public function getAccountPreviousBalance($data, $pre_balance)
    {
        if($this->balanceOperator == "+")
        {
            $data[$this->keyName][$this->account->id]['pre_balance'] = $pre_balance;
        } else {
            $data[$this->keyName][$this->account->id]['pre_balance'] = (-1)*$pre_balance;
        }

        return $data;
    }


    public function getAccountDrCrBalance($data, $result)
    {
        $data[$this->keyName][$this->account->id]['total_dr'] = $result['total_dr'];
        $data[$this->keyName][$this->account->id]['total_cr'] = $result['total_cr'];

        return $data;
    }





}
