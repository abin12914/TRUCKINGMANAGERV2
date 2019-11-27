<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty($selectedRelation) ? 'selected' : '' }}>Select primary relation type</option>
    @php
        if($registrationFlag){
            //employee -> [index = 1] excluding the relationtype 'employee'[index = 1] for account register/update
            unset($accountRelations[array_search('Employee', config('constants.accountRelations'))]);
        }
    @endphp
    @if(!empty($accountRelations) && (count($accountRelations) > 0))
        @foreach($accountRelations as $key => $relation)
            <option value="{{ $key }}" {{ $selectedRelation == $key ? 'selected' : '' }}>
                {{ $relation }}
            </option>
        @endforeach
    @endif
</select>
