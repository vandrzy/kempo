<div class="form-group">
    <label for="">Nama Pengguna <span style="color: red;">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', @$user->name) }}" placeholder="Nama Pengguna" id="">
    @error('name')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="">Email Pengguna <span style="color: red;">*</span></label>
    <input type="email" name="email" class="form-control" value="{{ old('email', @$user->email) }}" placeholder="Email Pengguna" id="">
    @error('email')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="form-group">
    @php
        if(isset($user)) {
            $password_required = '';
        } else {
            $password_required = '<span style="color: red;">*</span>';

        }
    @endphp
    <label for="">Password Pengguna  <?php echo $password_required ?> </label>
    <input type="password" name="password" class="form-control" placeholder="Password Pengguna" id="">
    @error('password')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
