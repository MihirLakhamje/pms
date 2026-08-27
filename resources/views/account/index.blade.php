<x-layout>
    <x-slot name="title">Account</x-slot>

    <div class="gap-6 mt-5">    

        {{-- RIGHT CONTENT --}}
        <div class="flex flex-col gap-6">

            {{-- PROFILE SETTINGS --}}
            <div class="card bg-base-100 border border-base-content/10 shadow-sm p-6">

                <div class="mb-6">
                    <h3 class="text-xl font-semibold">
                        Profile Information
                    </h3>

                    <p class="text-sm text-base-content/60 mt-1">
                        Update your account details and avatar.
                    </p>
                </div>

                <form method="POST" action="{{ route('account.updateProfile') }}" enctype="multipart/form-data"
                    class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @csrf
                    @method('PATCH')

                    {{-- Avatar --}}
                    <div>
                        <label class="label-text font-medium">
                            Avatar
                        </label>

                        <input type="file" name="profile_image" class="input" accept="image/*">
                    </div>

                    {{-- Name --}}
                    <div>
                        <label class="label-text font-medium">
                            Name
                        </label>

                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                            class="input input-bordered w-full">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="label-text font-medium">
                            Email
                        </label>

                        <input type="email" value="{{ old('email', Auth::user()->email) }}"
                            class="input input-bordered w-full" disabled>
                    </div>

                    <div class="md:col-span-3 flex justify-end">
                        <button class="btn btn-primary">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- SECURITY --}}
            <div class="card bg-base-100 border border-base-content/10 shadow-sm p-6">

                <div class="mb-6">
                    <h3 class="text-xl font-semibold">
                        Security
                    </h3>

                    <p class="text-sm text-base-content/60 mt-1">
                        Update your password securely.
                    </p>
                </div>

                <form method="POST" action="{{ route('account.updatePassword') }}"
                    class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="label-text font-medium">
                            Current Password
                        </label>

                        <input type="password" name="current_password" class="input input-bordered w-full">
                    </div>

                    <div>
                        <label class="label-text font-medium">
                            New Password
                        </label>

                        <input type="password" name="password" class="input input-bordered w-full">
                    </div>

                    <div>
                        <label class="label-text font-medium">
                            Confirm Password
                        </label>

                        <input type="password" name="password_confirmation" class="input input-bordered w-full">
                    </div>

                    <div class="md:col-span-3 flex justify-end">
                        <button class="btn btn-primary">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layout>
