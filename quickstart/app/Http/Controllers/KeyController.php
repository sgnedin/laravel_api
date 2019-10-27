<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\KeyModel; //Моделька для работы с бд
use Validator;

class KeyController extends Controller
{
    /**
     * Получаем все ключи
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(KeyModel::get(), 200);
    }

    /**
     * Генерация и запись в бд
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'type' => 'required|string|in:num,str,guid,sn',
            'length'=>'required|min:5|max:25|integer'
        ];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails())
        {
            return response()->json($validator->errors(), 400);
        }else
        {
            $type = $request->type;
            $length = $request->length;

            switch( $type )
            {
                case ('num'): 
                    $key = KeyModel::create(['gen_key' => $this->generate_num($length)]);
                    return response()->json($key, 200);
                    break;
                case ('str'):
                    $key = KeyModel::create(['gen_key' => $this->generate_str($length)]);
                    return response()->json($key, 200);
                    break;
                case ('guid'):
                    $key = KeyModel::create(['gen_key' => $this->generate_token($length, true)]);
                    return response()->json($key, 200);
                    break;
                case ('sn'):
                    $key = KeyModel::create(['gen_key' => $this->generate_token($length, false)]);
                    return response()->json($key, 200);
                    break;
            }
        }
    }

    /**
     * Получение ключа по id
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $key = KeyModel::find($id);
        if(is_null($key))
        {
            return response()->json('Key is not found', 404);
        }else
        {
            return response()->json($key, 200);
        }
    }

    //Тут функции для генерации чисел, строк и guid
    function generate_str($length)
    {
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }

    public function generate_token($length, $param)
    {
        // if $param == true => guid
        //else $param == false => numstr
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        $ch = 8;
        if ( $param )
        {
            for ($i=0; $i < $length; $i++) {
                if( $i == $ch)
                {
                    $token .= '-' . $codeAlphabet[random_int(0, $max-1)];
                    $ch +=8;
                }else
                {
                    $token .= $codeAlphabet[random_int(0, $max-1)];
                }
            }
        }else
        {
            for ($i=0; $i < $length; $i++) {
                $token .= $codeAlphabet[random_int(0, $max-1)];
            }
        }

       return $token;
    }

    public function generate_num($length)
    {
        $result = '';
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }
}
