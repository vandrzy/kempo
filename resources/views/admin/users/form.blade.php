<div class="form-group">
    <label for="">Nama Pengguna</label>
    <input type="text" name="name" class="form-control" value="{{ old('name', @$user->name) }}" placeholder="Nama Pengguna" id="">
    @error('name')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="form-group">
    <label for="">Email Pengguna</label>
    <input type="email" name="email" class="form-control" value="{{ old('email', @$user->email) }}" placeholder="Email Pengguna" id="">
    @error('email')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="form-group">
    <label for="">Password Pengguna</label>
    <input type="password" name="password" class="form-control" placeholder="Password Pengguna" id="">
    @error('password')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
