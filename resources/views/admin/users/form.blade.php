<div class="mb-4">
    <label for="">Nama Pengguna : <span style="color: red;">*</span></label>
    <input type="text" name="name" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('name', @$user->name) }}" placeholder="Nama Pengguna" id="">
    @error('name')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label for="">Email Pengguna : <span style="color: red;">*</span></label>
    <input type="email" name="email" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" value="{{ old('email', @$user->email) }}" placeholder="Email Pengguna" id="">
    @error('email')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
<div class="mb-4">
    @php
        if(isset($user)) {
            $password_required = '';
        } else {
            $password_required = '<span style="color: red;">*</span>';

        }
    @endphp
    <label for="">Password Pengguna : <?php echo $password_required ?> </label>
    <input type="password" name="password" class="px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:ring-blue-200 focus:border-blue-500 w-full" placeholder="Password Pengguna" id="">
    @error('password')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
