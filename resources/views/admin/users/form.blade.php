<div class="mb-4">
    <label for="">Nama Pengguna : <span style="color: red;">*</span></label>
    <input type="text" name="name" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" value="{{ old('name', @$user->name) }}" placeholder="Nama Pengguna" id="" />
    @error('name')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>

<div class="mb-4">
    <label for="">Email Pengguna : <span style="color: red;">*</span></label>
    <input type="email" name="email" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" value="{{ old('email', @$user->email) }}" placeholder="Email Pengguna" id="" />
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
    <input type="password" name="password" class="w-full pl-9 text-sm focus:shadow-primary-outline ease leading-5.6 relative -ml-px block min-w-0 flex-auto rounded-lg border border-solid border-gray-300 dark:bg-slate-850 dark:text-white bg-white bg-clip-padding py-2 pr-3 text-gray-700 transition-all placeholder:text-gray-500 focus:border-blue-500 focus:outline-none focus:transition-shadow" placeholder="Password Pengguna" id="" />
    @error('password')
        <span class="text-red" style="color: red;">{{ $message }}</span>
    @enderror
</div>
