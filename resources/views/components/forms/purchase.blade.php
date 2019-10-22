<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="supplier_account_id" class="control-label"><b style="color: red;">* </b> Supplier : </label>
            {{-- adding account select component --}}
            @component('components.selects.accounts', ['selectedAccountId' => old('supplier_account_id', (!empty($transportation) ? $transportation->purchase->transaction->credit_account_id : null)), 'cashAccountFlag' => true, 'selectName' => 'supplier_account_id', 'activeFlag' => false, 'tabindex' => 5])
            @endcomponent
            @if(!empty($errors->first('supplier_account_id')))
                <p style="color: red;" >{{$errors->first('supplier_account_id')}}</p>
            @endif
        </div>
        <div class="col-md-6">
            <label for="purchase_date" class="control-label"><b style="color: red;">* </b> Purchase Date : </label>
            <input type="text" class="form-control decimal_number_only datepicker_reg" name="purchase_date" id="purchase_date" placeholder="Purchase date" value="{{ old('purchase_date', (!empty($transportation) ? $transportation->purchase->transaction->transaction_date->format('d-m-Y') : null)) }}" tabindex="15">
            @if(!empty($errors->first('purchase_date')))
                <p style="color: red;" >{{$errors->first('purchase_date')}}</p>
            @endif
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="purchase_measure_type" class="control-label"><b style="color: red;">* </b> Measure Type : </label>
            {{-- adding rent type select component --}}
            @component('components.selects.measure-type', ['selectedMeasureTypeId' => old('purchase_measure_type', (!empty($transportation) ? $transportation->purchase->measure_type : null)), 'selectName' => 'purchase_measure_type', 'activeFlag' => false, 'tabindex' => 10])
            @endcomponent
            @if(!empty($errors->first('purchase_measure_type')))
                <p style="color: red;" >{{$errors->first('purchase_measure_type')}}</p>
            @endif
        </div>
        <div class="col-md-6">
            <label for="purchase_quantity" class="control-label"><b style="color: red;">* </b> Measurement/Quantity : </label>
            <input type="text" class="form-control decimal_number_only" name="purchase_quantity" id="purchase_quantity" placeholder="Measurement/Quantity"
                value="{{ old('purchase_measure_type', (!empty($transportation) ? $transportation->purchase->measure_type : null)) == 3 ? 1 : old('purchase_quantity', (!empty($transportation) ? $transportation->purchase->quantity : null)) }}"
                {{ old('purchase_measure_type', (!empty($transportation) ? $transportation->purchase->measure_type : null)) == 3 ? "readonly" : "" }} tabindex="17">
            @if(!empty($errors->first('purchase_quantity')))
                <p style="color: red;" >{{$errors->first('purchase_quantity')}}</p>
            @endif
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="purchase_rate" class="control-label"><b style="color: red;">* </b> Unit Rate : </label>
            <input type="text" class="form-control decimal_number_only" name="purchase_rate" id="purchase_rate" placeholder="Purchase rate" value="{{ old('purchase_rate', (!empty($transportation) ? $transportation->purchase->rate : null)) }}" tabindex="18">
            @if(!empty($errors->first('purchase_rate')))
                <p style="color: red;" >{{$errors->first('purchase_rate')}}</p>
            @endif
        </div>
        <div class="col-md-6">
            <label for="purchase_bill" class="control-label"><b style="color: red;">* </b> Bill Amount : </label>
            <input type="text" class="form-control decimal_number_only" name="purchase_bill" id="purchase_bill" placeholder="Purchase bill" value="{{ old('purchase_bill', (!empty($transportation) ? ($transportation->purchase->purchase_trip_bill  - $transportation->purchase->discount) : null)) }}" readonly tabindex="31">
            @if(!empty($errors->first('purchase_bill')))
                <p style="color: red;" >{{$errors->first('purchase_bill')}}</p>
            @endif
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-3">
            <label for="purchase_discount" class="control-label"><b style="color: red;">* </b> Discount : </label>
            <input type="text" class="form-control decimal_number_only" name="purchase_discount" id="purchase_discount" placeholder="Purchase discount" value="{{ old('purchase_discount' , (!empty($transportation) ? $transportation->purchase->purchase_discount : null)) ?? 0 }}" tabindex="19">
            @if(!empty($errors->first('purchase_discount')))
                <p style="color: red;" >{{$errors->first('purchase_discount')}}</p>
            @endif
        </div>
        <div class="col-md-3">
            <label for="purchase_trip_bill" class="control-label"><b style="color: red;">* </b> Trip Bill : </label>
            <input type="text" class="form-control decimal_number_only" name="purchase_trip_bill" id="purchase_trip_bill" placeholder="Total purchase bill" value="{{ old('purchase_trip_bill', (!empty($transportation) ? $transportation->purchase->purchase_trip_bill : null)) ?? 0 }}" readonly tabindex="32">
            @if(!empty($errors->first('purchase_trip_bill')))
                <p style="color: red;" >{{$errors->first('purchase_trip_bill')}}</p>
            @endif
        </div>
        <div class="col-md-3">
            <label for="purchase_no_of_trip" class="control-label"><b style="color: red;">* </b> No Of Transportations: </label>
            <input type="text" class="form-control decimal_number_only" name="purchase_no_of_trip" id="purchase_no_of_trip" placeholder="No of transportations" value="{{ old('no_of_trip', (!empty($transportation) ? $transportation->purchase->no_of_trip : null)) }}" readonly tabindex="34">
            @if(!empty($errors->first('purchase_no_of_trip')))
                <p style="color: red;" >{{$errors->first('purchase_no_of_trip')}}</p>
            @endif
        </div>
        <div class="col-md-3">
            <label for="purchase_total_bill" class="control-label"><b style="color: red;">* </b> Total Purchase Bill : </label>
            <input type="text" class="form-control decimal_number_only" name="purchase_total_bill" id="purchase_total_bill" placeholder="Total purchase bill" value="{{ old('purchase_total_bill', (!empty($transportation) ? $transportation->purchase->total_amount : null)) }}" readonly tabindex="34">
            @if(!empty($errors->first('purchase_total_bill')))
                <p style="color: red;" >{{$errors->first('purchase_total_bill')}}</p>
            @endif
        </div>
    </div>
</div>
