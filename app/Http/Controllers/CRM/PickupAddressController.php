<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Http\Requests\PickupAddressRequest;
use App\Models\PickupAddress;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PickupAddressController extends Controller
{
    public function index(Request $request)
    {
        try {
            $data['title'] = 'Pickup Address';    
            return view('CRM.PickupAddress.index', $data);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function store(PickupAddressRequest $request)
    {
        try {
            $pickupAddress = new PickupAddress();
            $pickupAddress->name = $request->name;
            $pickupAddress->email = $request->email;
            $pickupAddress->phone = $request->phone;
            $pickupAddress->type = $request->type;
            $pickupAddress->location = $request->location;
            $pickupAddress->address = $request->address;
            $pickupAddress->city = $request->city;
            $pickupAddress->state = $request->state;
            $pickupAddress->pincode = (int)$request->pincode;
            $pickupAddress->status = $request->status ? 1 : 0;
            $pickupAddress->user_id = Auth::user()->_id;
            
            if ($pickupAddress->save()) {
                return $this->successMsg('Pickup address created successfully');
            }
            return $this->failMsg('Pickup address not created');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $record = PickupAddress::findOrFail($id);
            return $this->successMsg('Pickup address fetched successfully', $record);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function update(PickupAddressRequest $request, $id)
    {
        try {
            $pickupAddress = PickupAddress::findOrFail($id);
            $pickupAddress->name = $request->name;
            $pickupAddress->email = $request->email;
            $pickupAddress->phone = $request->phone;
            $pickupAddress->type = $request->type;
            $pickupAddress->location = $request->location;
            $pickupAddress->address = $request->address;
            $pickupAddress->city = $request->city;
            $pickupAddress->state = $request->state;
            $pickupAddress->pincode = (int)$request->pincode;
            $pickupAddress->status = $request->status ? 1 : 0;

            if ($pickupAddress->save()) {
                return $this->successMsg('Pickup address updated successfully');
            }
            return $this->failMsg('Pickup address not updated');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|exists:pickup_addresses,_id',
                'status' => 'required|boolean'
            ]);

            if ($validator->fails()) {
                return $this->failMsg($validator->errors()->first());
            }

            $pickupAddress = PickupAddress::findOrFail($request->id);
            $pickupAddress->status = (int) $request->status;

            if ($pickupAddress->save()) {
                return $this->successMsg('Status updated successfully');
            }
            return $this->failMsg('Status not updated');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pickupAddress = PickupAddress::findOrFail($id);

            if ($pickupAddress->delete()) {
                return $this->successMsg('Pickup address deleted successfully');
            }
            return $this->failMsg('Pickup address not deleted');
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function datatable(Request $request)
    {
        $query = PickupAddress::query();

        // Filtering
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%");
            });
        }
        
        if ($request->type) {
            $query->where('type', $request->type);
        }
        
        if ($request->status || $request->status == 0 && $request->status !== null) {
            $query->where('status', (int)$request->status);
        }

        $total = $query->count();

        // Ordering
        $columns = $request->columns;
        if ($request->order && count($request->order)) {
            foreach ($request->order as $order) {
                $colIdx = $order['column'];
                $colName = $columns[$colIdx]['data'];
                $dir = $order['dir'];
                if ($colName !== 'index' && $colName !== 'actions') {
                    $query->orderBy($colName, $dir);
                }
            }
        }

        // Pagination
        $start = $request->start ?? 0;
        $length = $request->length ?? 10;
        $pickupAddresses = $query->skip($start)->take($length)->get();

        $data = $pickupAddresses->map(function ($pickupAddress, $key) use ($start) {
            $statusSwitch = '<div class="form-check form-switch">'
                . '<input type="checkbox" class="form-check-input status-switch" data-id="' . $pickupAddress->_id . '" ' . ($pickupAddress->status ? 'checked' : '') . '>'
                . '<label class="form-check-label" for="status' . $pickupAddress->_id . '"></label>'
                . '</div>';
                
            $typeBadge = '';
            switch($pickupAddress->type) {
                case 'pickup_address':
                    $typeBadge = '<span class="badge bg-primary">Pickup Address</span>';
                    break;
                case 'rto_address':
                    $typeBadge = '<span class="badge bg-warning">RTO Address</span>';
                    break;
                case 'return_address':
                    $typeBadge = '<span class="badge bg-info">Return Address</span>';
                    break;
            }
            
            return [
                'index' => $start + $key + 1,
                '_id' => $pickupAddress->_id,
                'name' => $pickupAddress->name,
                'contact_info' => '<div><strong>' . $pickupAddress->email . '</strong><br><small class="text-muted">' . $pickupAddress->phone . '</small></div>',
                'type' => $typeBadge,
                'location' => $pickupAddress->location,
                'address' => $pickupAddress->address,
                'location_details' => '<div><strong>' . $pickupAddress->city . '</strong><br><small class="text-muted">' . $pickupAddress->state . ' - ' . $pickupAddress->pincode . '</small></div>',
                'status' => $statusSwitch,
                'created' => $pickupAddress->dFormat($pickupAddress->created),
            ];
        });

        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data,
        ]);
    }

    public function getByType(Request $request)
    {
        try {
            $type = $request->type;
            $addresses = PickupAddress::where('type', $type)
                                    ->where('status', 1)
                                    ->orderBy('name', 'asc')
                                    ->get();
            
            return $this->successMsg('Addresses fetched successfully', $addresses);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $pickupAddress = PickupAddress::findOrFail($id);
            return $this->successMsg('Address fetched successfully', $pickupAddress);
        } catch (Exception $e) {
            return $this->failMsg($e->getMessage());
        }
    }
} 