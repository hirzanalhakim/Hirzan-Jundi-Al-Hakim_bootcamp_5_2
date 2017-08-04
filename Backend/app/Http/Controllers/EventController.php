<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\Event;
use App\Order;

class EventController extends Controller
{
    function getData(){

        $eventList = Event::get();

        return response()->json($eventList, 200);
    }

    function buyTicket(Request $request){

        DB::beginTransaction();

        try{
            $this->validate($request, [
            'id_event' => 'required',
            'guest_email' => 'required|email'
            ]);

            $id = $request->input('id_event');
            $email = $request->input('guest_email');
            $ticketCode = str_random(40);


            $order = new Order;
            $order->id_event = $id;
            $order->guest_email = $email;
            $order->ticket_code = $ticketCode;
            $order->save();

            $event = Event::where('id_event', '=', $id)->decrement('ticket_amount');

            $orderList = Order::get();

            DB::commit(); // data tidak akan di insert sebelum di commit
            return response()->json($orderList, 200);
        }
        catch(\Exception $e){

            DB::rollback(); //untuk rollback data ketika data yang dimasukkan mengalami kegagalan
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    function addEvent(Request $request){

        DB::beginTransaction();

        try{
            $this->validate($request, [
            'name_event' => 'required',
            'date_event' => 'required',
            'ticket_price' => 'required',
            'ticket_amount' => 'required'

            ]);

            $name = $request->input('name_event');
            $date = $request->input('date_event');
            $price = $request->input('ticket_price');
            $amount = $request->input('ticket_amount');


            $addEvent = new Event;
            $addEvent->name_event = $name;
            $addEvent->date_event = $date;
            $addEvent->ticket_price = $price;
            $addEvent->ticket_amount = $amount;
            $addEvent->save();

            $eventList = Event::get();

            DB::commit(); // data tidak akan di insert sebelum di commit
            return response()->json($eventList, 200);
        }
        catch(\Exception $e){

            DB::rollback(); //untuk rollback data ketika data yang dimasukkan mengalami kegagalan
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }
}
