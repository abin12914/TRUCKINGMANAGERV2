<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty(old($selectName)) ? 'selected' : '' }}>Select primary relation type</option>
    @php
        if($registrationFlag){
            //employee -> [index = 1] excluding the relationtype 'employee'[index = 1] for account register/update
            unset($accountRelations[1]);
        }
    @endphp
    @if(!empty($accountRelations) && (count($accountRelations) > 0))
        @foreach($accountRelations as $key => $relation)
            <option value="{{ $key }}" {{ (old($selectName) == $key || $selectedRelation == $key) ? 'selected' : '' }}>
                {{ $relation }}
            </option>
        @endforeach
    @endif
</select>
