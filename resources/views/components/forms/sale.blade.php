<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="customer_account_id" class="control-label"><b style="color: red;">* </b> Customer : </label>
            {{-- adding account select component --}}
            @component('components.selects.accounts', ['selectedAccountId' => old('customer_account_id', (!empty($transportation) ? $transportation->sale->transaction->debit_account_id : null)), 'cashAccountFlag' => true, 'selectName' => 'customer_account_id', 'activeFlag' => false, 'tabindex' => 1])
            @endcomponent
            {{-- adding error_message p tag component --}}
            @component('components.paragraph.error_message', ['fieldName' => 'customer_account_id'])
            @endcomponent
        </div>
        <div class="col-md-6">
            <label for="sale_date" class="control-label"><b style="color: red;">* </b> Sale Date : </label>
            <input type="text" class="form-control decimal_number_only datepicker_reg" name="sale_date" id="sale_date" placeholder="Sale date" value="{{ old('sale_date', (!empty($transportation) ? $transportation->sale->transaction->transaction_date->format('d-m-Y') : null)) }}" tabindex="2">
            {{-- adding error_message p tag component --}}
            @component('components.paragraph.error_message', ['fieldName' => 'sale_date'])
            @endcomponent
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="sale_measure_type" class="control-label"><b style="color: red;">* </b> Measure Type : </label>
            {{-- adding rent type select component --}}
            @component('components.selects.measure-type', ['selectedMeasureTypeId' => old('sale_measure_type', (!empty($transportation) ? $transportation->sale->measure_type : '')), 'selectName' => 'sale_measure_type', 'activeFlag' => false, 'tabindex' => 3])
            @endcomponent
            {{-- adding error_message p tag component --}}
            @component('components.paragraph.error_message', ['fieldName' => 'sale_measure_type'])
            @endcomponent
        </div>
        <div class="col-md-6">
            <label for="sale_quantity" class="control-label"><b style="color: red;">* </b> Measurement/Quantity : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_quantity" id="sale_quantity" placeholder="Measurement/Quantity"
                value="{{ old('sale_quantity', (!empty($transportation) ? $transportation->sale->quantity : null)) }}" {{ old('sale_measure_type', (!empty($transportation) ? $transportation->sale->measure_type : null)) == 3 ? "readonly" : "" }}" tabindex="4">
                {{-- adding error_message p tag component --}}
                @component('components.paragraph.error_message', ['fieldName' => 'sale_quantity'])
                @endcomponent
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <label for="sale_rate" class="control-label"><b style="color: red;">* </b> Unit Rate : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_rate" id="sale_rate" placeholder="Sale rate" value="{{ old('sale_rate' , (!empty($transportation) ? $transportation->sale->rate : null)) }}" tabindex="5">
            {{-- adding error_message p tag component --}}
            @component('components.paragraph.error_message', ['fieldName' => 'sale_rate'])
            @endcomponent
        </div>
        <div class="col-md-6">
            <label for="sale_bill" class="control-label"><b style="color: red;">* </b> Bill Amount : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_bill" id="sale_bill" placeholder="Bill amount" value="{{ old('sale_bill' , (!empty($transportation) ? ($transportation->sale->sale_trip_bill - $transportation->sale->discount) : null)) }}" readonly tabindex="-1">
            {{-- adding error_message p tag component --}}
            @component('components.paragraph.error_message', ['fieldName' => 'sale_bill'])
            @endcomponent
        </div>
    </div>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-3">
            <label for="sale_discount" class="control-label"><b style="color: red;">* </b> Discount : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_discount" id="sale_discount" placeholder="Sale discount" value="{{ old('sale_discount', (!empty($transportation) ? $transportation->sale->discount : null)) ?? 0 }}" tabindex="6">
            {{-- adding error_message p tag component --}}
            @component('components.paragraph.error_message', ['fieldName' => 'sale_discount'])
            @endcomponent
        </div>
        <div class="col-md-3">
            <label for="sale_trip_bill" class="control-label"><b style="color: red;">* </b> Trip Bill : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_trip_bill" id="sale_trip_bill" placeholder="Trip sale bill" value="{{ old('sale_trip_bill' , (!empty($transportation) ? $transportation->sale->sale_trip_bill : null)) }}" readonly tabindex="-1">
            {{-- adding error_message p tag component --}}
            @component('components.paragraph.error_message', ['fieldName' => 'sale_trip_bill'])
            @endcomponent
        </div>
        <div class="col-md-3">
            <label for="sale_no_of_trip" class="control-label"><b style="color: red;">* </b> No Of Transportations: </label>
            <input type="text" class="form-control decimal_number_only" name="sale_no_of_trip" id="sale_no_of_trip" placeholder="No of transportations" value="{{ old('no_of_trip' , (!empty($transportation) ? $transportation->sale->no_of_trip : null)) }}" readonly tabindex="-1">
            {{-- adding error_message p tag component --}}
            @component('components.paragraph.error_message', ['fieldName' => 'sale_no_of_trip'])
            @endcomponent
        </div>
        <div class="col-md-3">
            <label for="sale__total_bill" class="control-label"><b style="color: red;">* </b> Total Sale Bill : </label>
            <input type="text" class="form-control decimal_number_only" name="sale_total_bill" id="sale_total_bill" placeholder="Total sale bill" value="{{ old('sale_total_bill' , (!empty($transportation) ? $transportation->sale->total_amount : null)) }}" readonly tabindex="-1">
            {{-- adding error_message p tag component --}}
            @component('components.paragraph.error_message', ['fieldName' => 'sale__total_bill'])
            @endcomponent
        </div>
    </div>
</div>
