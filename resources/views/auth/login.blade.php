<x-layout>
    <div class="register-page flex-grow-1 d-flex align-items-center justify-content-center">
        <form class="card register-card login-card bg-dark text-light" method="POST" action="/login">
            <h1 class="register-title">LOGIN</h1>
            @csrf
            <div class="card-body register-card-body login-card-body">
                <div class="register-field">
                    <label for="login" class="form-label">Username</label>
                    <input type="text" name= "login" class="form-control form-control-lg" id="name" placeholder = "Username or Email" required>
                </div>

                <div class="register-field">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder = "........" required>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="remember-control mt-3">
                    <label class="d-flex align-items-center gap-2 mb-0">
                        <input type="checkbox"
                                name="remember"
                                class="form-check-input m-0">
                        <span>Remember me</span>
                    </label>
                </div>
                @error('login')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
                <button type="submit" class="btn btn-primary py-2">Login</button>
            </div>
        </form>
    </div>
</x-layout>