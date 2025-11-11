<x-layout>
    <div
        class="flex min-h-screen items-center justify-center overflow-x-hidden bg-base-200 py-10">
        <div class="relative flex items-center justify-center px-4 sm:px-6 lg:px-8">
            <div
                class="bg-base-100 shadow-base-300/20 z-1 w-full space-y-6 rounded-xl p-6 shadow-md sm:min-w-md lg:p-8">
                <div class="flex items-center gap-3">
                    <img src="https://cdn.flyonui.com/fy-assets/logo/logo.png" class="size-8" alt="brand-logo" />
                    <h2 class="text-base-content text-xl font-bold">PMS</h2>
                </div>
                <div>
                    <h3 class="text-base-content mb-1.5 text-2xl font-semibold">Reset Password</h3>
                </div>
                <div class="space-y-4">
                    <form class="mb-4 space-y-4" method="POST" action="{{ route('password.change') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div>
                            <label class="label-text" for="userEmail">Email address</label>
                            <input type="email" placeholder="Enter your email address" class="input {{ $errors->has('email') ? 'is-invalid' : '' }}" id="userEmail" name="email"/>
                            @error('email')
                                <span class="helper-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="label-text" for="userPassword">Password</label>
                            <div class="input">
                                <input id="userPassword" type="password" placeholder="············" class=" {{ $errors->has('password') ? 'is-invalid' : '' }}"  name="password"/>
                                <button type="button" data-toggle-password='{ "target": "#userPassword" }'
                                    class="block cursor-pointer" aria-label="userPassword">
                                    <span
                                        class="icon-[tabler--eye] password-active:block hidden size-5 shrink-0"></span>
                                    <span
                                        class="icon-[tabler--eye-off] password-active:hidden block size-5 shrink-0"></span>
                                </button>
                            </div>
                            @error('password')
                                <span class="helper-text">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="label-text" for="confirmPassword">Confirm Password</label>
                            <div class="input">
                                <input id="confirmPassword" type="password" placeholder="············" class=" {{ $errors->has('password') ? 'is-invalid' : '' }}"  name="password_confirmation"/>
                                <button type="button" data-toggle-password='{ "target": "#confirmPassword" }'
                                    class="block cursor-pointer" aria-label="confirmPassword">
                                    <span
                                        class="icon-[tabler--eye] password-active:block hidden size-5 shrink-0"></span>
                                    <span
                                        class="icon-[tabler--eye-off] password-active:hidden block size-5 shrink-0"></span>
                                </button>
                            </div>
                        </div>
                        <button class="btn btn-lg btn-primary btn-gradient btn-block">Reset Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>