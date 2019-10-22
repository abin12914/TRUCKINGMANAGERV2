<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="customer_account_id" class="control-label"><b style="color: red;">* </b> Customer : </label>
            {{-- adding account select component --}}
            @component('components.selects.accounts', ['selectedAccountId' => old('customer_account_id', (!empty($transportation) ? $transportation->sale->transaction->debit_account_id : null)), 'cashAccountFlag' => true, 'selectName' => 'customer_account_id', 'activeFlag' => false, 'tabindex' => 5])
            @endcomponent
            @if(!empty($errors->first('customer_account_id')))
                <p style="color: red;" >{{$errors->first('customer_account_id')}}</p>
            @endif
        </div>
        <div class="col-md-6">
            <label for="sale_date" class="control-label"><b style="color: red;">* </b> Sale Date : </label>
            <input type="text" class="form-control decimal_number_only datepicker_reg" name="sale_date" id="sale_date" placeholder="Sale date" value="{{ old('sale_date', (!empty($transportation) ? $transportation->sale->transaction->transaction_date->format('d-m-Y') : null)) }}" tabindex="22">
            @if(!empty($errors->first('sale_date')))
                <p style="color: red;" >{{$errors->first('sale_date')}}</p>
            @endif
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="sale_measure_type" class="control-label"><b style="color: red;">* </b> Measure Type : </label>
            {{-- adding rent type select component --}}
            @component('components.selects.measure-type', ['selectedMeasureTypeId' => old('sale_measure_type', (!empty($transportation) ? $transportation->sale->measure_type : '')), 'selectName' => 'sale_measure_type', 'activeFlag' => false, 'tabindex' => 10])
            @endcomponent
            @if(!empty($errors->first('sale_measure_type')))
                <p style="color: red;" >{{$errors->first('sale_measure_type')}}</p>
            @endif
        </div>
        <div class="col-md-6">
            <label for="sale_quantity" class="control-label"><b style="color: red;">* </b> Measurement/Quantity : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_quantity" id="sale_quantity" placeholder="Measurement/Quantity" value="{{ old('sale_quantity', (!empty($transportation) ? $transportation->sale->quantity : null)) }} {{ old('sale_measure_type', (!empty($transportation) ? $transportation->sale->measure_type : null)) == 3 ? "readonly" : "" }}" tabindex="24">
            @if(!empty($errors->first('sale_quantity')))
                <p style="color: red;" >{{$errors->first('sale_quantity')}}</p>
            @endif
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="sale_rate" class="control-label"><b style="color: red;">* </b> Unit Rate : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_rate" id="sale_rate" placeholder="Sale rate" value="{{ old('sale_rate' , (!empty($transportation) ? $transportation->sale->rate : null)) }}" tabindex="25">
            @if(!empty($errors->first('sale_rate')))
                <p style="color: red;" >{{$errors->first('sale_rate')}}</p>
            @endif
        </div>
        <div class="col-md-6">
            <label for="sale_bill" class="control-label"><b style="color: red;">* </b> Bill Amount : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_bill" id="sale_bill" placeholder="Bill amount" value="{{ old('sale_bill' , (!empty($transportation) ? ($transportation->sale->sale_trip_bill - $transportation->sale->discount) : null)) }}" readonly tabindex="33">
            @if(!empty($errors->first('sale_bill')))
                <p style="color: red;" >{{$errors->first('sale_bill')}}</p>
            @endif
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-3">
            <label for="sale_discount" class="control-label"><b style="color: red;">* </b> Discount : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_discount" id="sale_discount" placeholder="Sale discount" value="{{ old('sale_discount', (!empty($transportation) ? $transportation->sale->discount : null)) ?? 0 }}" tabindex="26">
            @if(!empty($errors->first('sale_discount')))
                <p style="color: red;" >{{$errors->first('sale_discount')}}</p>
            @endif
        </div>
        <div class="col-md-3">
            <label for="sale_trip_bill" class="control-label"><b style="color: red;">* </b> Trip Bill : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_trip_bill" id="sale_trip_bill" placeholder="Trip sale bill" value="{{ old('sale_trip_bill' , (!empty($transportation) ? $transportation->sale->sale_trip_bill : null)) }}" readonly tabindex="34">
            @if(!empty($errors->first('sale_trip_bill')))
                <p style="color: red;" >{{$errors->first('sale_trip_bill')}}</p>
            @endif
        </div>
        <div class="col-md-3">
            <label for="sale_no_of_trip" class="control-label"><b style="color: red;">* </b> No Of Transportations: </label>
            <input type="text" class="form-control decimal_number_only" name="sale_no_of_trip" id="sale_no_of_trip" placeholder="No of transportations" value="{{ old('no_of_trip' , (!empty($transportation) ? $transportation->sale->no_of_trip : null)) }}" readonly tabindex="34">
            @if(!empty($errors->first('sale_no_of_trip')))
                <p style="color: red;" >{{$errors->first('sale_no_of_trip')}}</p>
            @endif
        </div>
        <div class="col-md-3">
            <label for="sale__total_bill" class="control-label"><b style="color: red;">* </b> Total Sale Bill : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_total_bill" id="sale_total_bill" placeholder="Total sale bill" value="{{ old('sale_total_bill' , (!empty($transportation) ? $transportation->sale->total_amount : null)) }}" readonly tabindex="34">
            @if(!empty($errors->first('sale_total_bill')))
                <p style="color: red;" >{{$errors->first('sale_total_bill')}}</p>
            @endif
        </div>
    </div>
</div>
