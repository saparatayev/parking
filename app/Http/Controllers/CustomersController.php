<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomersController extends Controller
{
    public function index() {
        
        return view('customers')->with([
            'customers' => Customer::with('bookings')->paginate(4)
        ]);
    }

    public function create(Request $request) {
        if($request->isMethod('post')){

            $result = $request->all();

            $input = array();

            $validator = \Validator::make($result,[
                'fio'=>'required',
                'email'=>'required|email|unique:customers,email',
                'phone'=>'required',
            ]);
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['fio'] = $result['fio'];
            $input['email'] = $result['email'];
            $input['phone'] = $result['phone'];

            $customer = new Customer();
            $customer->fill($input);
            
            if($customer->save()) {
                return redirect()->route('customers')->with('status','Клиент добавлен');
            } else {
                return redirect()->back()->with('error','Ошибка добавления');
            }
        }

        return view('new_customer');
    }
    
    public function edit($id, Request $request) {
        $old = Customer::findOrFail($id);
        
        if($request->isMethod('post')){

            $result = $request->all();

            $input = array();

            $validator = \Validator::make($result,[
                'fio'=>'required',
                'email'=>'required|email|unique:customers,email,'.$result['customer_id'],
                'phone'=>'required',
            ]);
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $input['fio'] = $result['fio'];
            $input['email'] = $result['email'];
            $input['phone'] = $result['phone'];

            $old->fill($input);
            
            if($old->update()) {
                return redirect()->route('customers')->with('status','Клиент изменен');
            } else {
                return redirect()->back()->with('error','Ошибка');
            }
        }

        return view('edit_customer', compact('old'));
    }

    public function delete($id, Request $request) {
        if($request->isMethod('delete')) {
            $old = Customer::with('bookings')->findOrFail($id);

            if(count($old->bookings) > 0) {
                return redirect()->route('customers')->with('can_not_delete','У этого клиента есть бронь');
            }

            $old->delete();
            return redirect()->route('customers')->with('status','Данные о клиенте удалены');
        }
    }

    public function findByEmail($email) {
        $customer = Customer::where('email', $email)->first();

        return response()->json([ 'customer' => $customer]);
    }
}
