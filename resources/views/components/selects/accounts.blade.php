<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="" {{ empty($selectedAccountId) ? 'selected' : '' }}>Select account</option>
    @if(!empty($accountsCombo) && (count($accountsCombo) > 0))
        @foreach($accountsCombo as $account)
            @if(!$cashAccountFlag && $account->type != array_search('Personal', config('constants.accountTypes'))) {{-- type != 3 means not personal account --}}
                @continue
            @endif
            @if($activeFlag && $account->status != 1)
                @continue
            @endif
            <option value="{{ $account->id }}" {{ $selectedAccountId == $account->id ? 'selected' : '' }}>
                {{ $account->account_name }}
            </option>
        @endforeach
    @endif
</select>
