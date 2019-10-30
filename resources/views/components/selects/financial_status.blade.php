<select class="form-control select2" name="financial_status" id="financial_status" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty($selectedStatus) ? 'selected' : '' }}>Select status</option>
    <option value="0" {{ $selectedStatus == '0' ? 'selected' : '' }}>None (No pending transactions)</option>
    <option value="2" {{ $selectedStatus == '2' ? 'selected' : '' }}>Debitor (Account Holder Owe Company)</option>
    <option value="1" {{ $selectedStatus == '1' ? 'selected' : '' }}>Creditor (Company Owe Account Holder)</option>
</select>
