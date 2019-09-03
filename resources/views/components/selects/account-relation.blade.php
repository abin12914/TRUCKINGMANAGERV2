<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty(old($selectName)) ? 'selected' : '' }}>Select primary relation type</option>
    @if(!empty($accountRelations) && (count($accountRelations) > 0))
        @foreach($accountRelations as $key => $relation)
            <option value="{{ $key }}" {{ (old($selectName) == $key || $selectedRelation == $key) ? 'selected' : '' }}>
                {{ $relation }}
            </option>
        @endforeach
    @endif
</select>