<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty(old($selectName)) ? 'selected' : '' }}>Select primary relation type</option>
    @if(!empty($accountRelationTypes) && (count($accountRelationTypes) > 0))
        @foreach($accountRelationTypes as $key => $relationType)
            <option value="{{ $key }}" {{ (old($selectName) == $key || $selectedRelationType == $key) ? 'selected' : '' }}>
                {{ $relationType }}
            </option>
        @endforeach
    @endif
</select>