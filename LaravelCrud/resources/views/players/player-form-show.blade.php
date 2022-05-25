<form method="POST" action="{{ url('players') }}">
    @csrf
    <div class="form-group col-xl-5 col-lg-6 col-md-8 col-sm-12 mx-auto text-left form p-4">
        <h1>Show Player</h1>
        <fieldset disabled>
        <div class="form-group">
            <label for="name">Name</label>
            <input
                value="{{$player->name}}"
                type="text"
                id="name"
                name="name"
                autocomplete="name"
                placeholder="Type your name"
                class="form-control">
        </div>


        <div class="form-group">
            <label for="address">Address</label>
            <input
                value="{{$player->address}}"
                type="text"
                id="address"
                name="address"
                autocomplete="address"
                placeholder="Type your address"
                class="form-control">

        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea
                value="{{$player->description}}"
                class="form-control"
                name="description"
                id="description"
                rows="5"
                value="{{ old('description') }}"
                placeholder="Type your description"
                autocomplete="description">{{$player->description}}</textarea>
            @error('description')
            <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>
        <div>
            <br>
            <label for="retired">Retired:</label>
            <br>
            <div class="form-check form-check-inline">
                <input class="form-check-input " type="radio" name="retired" id="retired1" value="1"
                    @if($player->retired == 1){
                       checked="checked"
                       }
                    @endIf >
                <label class="form-check-label" for="retired1">
                    Yes
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="retired" id="retired0" value="0"
                       @if($player->retired == 0){
                       checked="checked"
                       }
                    @endIf>
                <label class="form-check-label" for="retired0">
                    No
                </label>
            </div>
        </div>
        <br>
        </fieldset>
        <div class="button col-xl-4 col-lg-6 col-md-8 col-sm-12 mx-auto text-left form p-4">

            <td><a class="btn btn-primary" href="{{url('players')}}" role="button">Back</a></td>

            {{-- <button type="submit" class="mt-2 mb-5 btn btn-primary">Back</button> --}}

        </div>
    </div>

</form>
