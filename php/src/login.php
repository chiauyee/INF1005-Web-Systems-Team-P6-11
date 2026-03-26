<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="/css/navigation.css">
        <link rel="stylesheet" href="/css/login.css">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    </head>
    <body>
        <?php include __DIR__ . '/includes/navigation.php'; ?>

        <main>
            <div class="login-wrapper">
                <div class="login-left">
                    <h2 class="panel-heading">Good to have<br>you <em>back.</em></h2>
                    <p class="panel-desc">Sign in to browse your collection, track orders and discover new records waiting for you.</p>
                    <div class="panel-stats">
                        <div class="stat-item">
                            <div class="stat-number">12k+</div>
                            <div class="stat-label">Listings</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">4k+</div>
                            <div class="stat-label">Sellers</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">80+</div>
                            <div class="stat-label">Genres</div>
                        </div>
                    </div>
                </div>

                <div class="login-right">
                    <div class="form-area">
                        <p class="form-eyebrow">Welcome back</p>
                        <h1 class="form-heading">Sign in</h1>
                        <p class="form-sub">Enter your credentials to access your account.</p>

                        <div id="error-msg" class="alert alert-danger" style="display:none;"></div>

                        <div id="login-form">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <div class="input-wrap">
                                    <i class="bi bi-person"></i>
                                    <input type="text" name="email" id="email" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password:</label>
                                <div class="input-wrap">
                                    <i class="bi bi-lock"></i>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                            </div>

                            <div class="mb-3 text-end">
                                <a href="forgot_password.php" class="register-prompt">Forgot password?</a>
                            </div>

                            <button type="button" id="btn-login" class="btn-login">Sign In 
                                <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>

                        <div id="otp-form" style="display:none;">
                            <p class="form-sub">A 6-digit code has been sent to your email.</p>
                            <div class="mb-3">
                                <label for="otp" class="form-label">Verification Code:</label>
                                <div class="input-wrap">
                                    <i class="bi bi-shield-lock"></i>
                                    <input type="text" id="otp" class="form-control" maxlength="6" placeholder="000000" required>
                                </div>
                            </div>

                            <button type="button" id="btn-verify-otp" class="btn-login">Verify 
                                <i class="bi bi-arrow-right"></i>
                            </button>

                            <p class="register-prompt mt-3">Didn't receive a code? 
                                <a href="#" id="resend-otp">Resend</a>
                            </p>
                        </div>

                        <p class="register-prompt">Don't have an account? <a href="register.php">Register here</a></p>
                    </div>
                </div>
            </div>
        </main>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
        <script>
            // Redirect after login
            function getUrlParameter(name) 
            {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            }
            let lockoutTimer = null;
            let countdownInterval = null;
            let isLockedOut = false;

            // Error messages for invalid login
            function showError(message) 
            {
                const errorDiv = document.getElementById('error-msg');
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
                
                // Auto-hide after 5 seconds for non-lockout errors
                setTimeout(() => 
                {
                    if (errorDiv.style.display === 'block' && !errorDiv.classList.contains('lockout-error')) 
                    {
                        errorDiv.style.display = 'none';
                    }
                }, 5000);
            }

            function showLockoutMessage(seconds) 
            {
                const errorDiv = document.getElementById('error-msg');
                errorDiv.classList.add('lockout-error');
                errorDiv.innerHTML = `
                    <div class="lockout-message">
                        <i class="bi bi-clock-history me-2"></i>
                        <strong>Too many failed attempts!</strong><br>
                        <span class="countdown-text">Please wait <span id="countdown-timer" class="countdown-number">${seconds}</span> seconds before trying again.</span>
                    </div>
                `;
                errorDiv.style.display = 'block';
                isLockedOut = true;
            }

            function updateLockoutMessage(seconds) 
            {
                const timerSpan = document.getElementById('countdown-timer');
                if (timerSpan) 
                {
                    timerSpan.textContent = seconds;
                }
                
                // Only update if we're still locked out
                if (isLockedOut && seconds > 0) 
                {
                    const errorDiv = document.getElementById('error-msg');
                    if (errorDiv && errorDiv.classList.contains('lockout-error')) 
                    {
                        errorDiv.innerHTML = `
                            <div class="lockout-message">
                                <i class="bi bi-clock-history me-2"></i>
                                <strong>Too many failed attempts!</strong><br>
                                <span class="countdown-text">Please wait <span id="countdown-timer" class="countdown-number">${seconds}</span> seconds before trying again.</span>
                            </div>
                        `;
                    }
                }
            }

            function hideLockoutMessage() 
            {
                const errorDiv = document.getElementById('error-msg');
                errorDiv.classList.remove('lockout-error');
                errorDiv.style.display = 'none';
                isLockedOut = false;
            }

            function disableForm(disabled) 
            {
                const emailInput = document.getElementById('email');
                const passwordInput = document.getElementById('password');
                const loginBtn = document.getElementById('btn-login');
                
                emailInput.disabled = disabled;
                passwordInput.disabled = disabled;
                loginBtn.disabled = disabled;
                
                if (disabled) 
                {
                    loginBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Please wait...';
                } 
                
                else 
                {
                    loginBtn.innerHTML = 'Sign In <i class="bi bi-arrow-right"></i>';
                }
            }

            // Start countdown for 30 seconds for invalid login
            function startCountdown(remainingSeconds) 
            {
                // Clear any existing intervals
                if (countdownInterval) 
                {
                    clearInterval(countdownInterval);
                }
                
                // Disable form immediately
                disableForm(true);
                
                // Show lockout message with initial seconds
                showLockoutMessage(remainingSeconds);
                
                // Start the countdown
                let seconds = remainingSeconds;
                countdownInterval = setInterval(() => 
                {
                    seconds--;
                    
                    if (seconds <= 0) 
                    {
                        // Countdown finished
                        clearInterval(countdownInterval);
                        countdownInterval = null;
                        disableForm(false);
                        hideLockoutMessage();
                    } 
                    
                    else 
                    {
                        // Update the countdown display
                        updateLockoutMessage(seconds);
                    }
                }, 1000);
            }

            // Check if user is already locked out
            function checkExistingLockout() 
            {
                const lockoutEndTime = sessionStorage.getItem('lockoutEndTime');
                if (lockoutEndTime) 
                {
                    const now = Date.now();
                    const remaining = Math.ceil((lockoutEndTime - now) / 1000);
                    
                    if (remaining > 0) 
                    {
                        startCountdown(remaining);
                    } 
                    
                    else 
                    {
                        sessionStorage.removeItem('lockoutEndTime');
                    }
                }
            }

            // Login form submission
            document.getElementById('btn-login').addEventListener('click', function () 
            {
                // Don't process if already locked out
                if (isLockedOut || countdownInterval) 
                {
                    console.log('Currently locked out, please wait');
                    return;
                }
                
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;
                const errorDiv = document.getElementById('error-msg');
                const loginBtn = this;
                
                // Get the redirect parameter from URL
                const redirect = getUrlParameter('redirect') || 'index.php';
                
                // Clear any existing timers
                if (lockoutTimer) clearTimeout(lockoutTimer);
                
                errorDiv.style.display = 'none';
                errorDiv.classList.remove('lockout-error');
                
                // Disable form while processing
                disableForm(true);

                // Validate email format
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!email) 
                {
                    showError('Please enter your email.');
                    disableForm(false);
                    return;
                }

                if (!emailRegex.test(email)) 
                {
                    showError('Please enter a valid email address.');
                    disableForm(false);
                    return;
                }

                if (!password) 
                {
                    showError('Please enter your password.');
                    disableForm(false);
                    return;
                }

                const body = new FormData();
                body.append('email', email);
                body.append('password', password);
                
                fetch('/api/login.php', { method: 'POST', body })
                    .then(r => r.json())
                    .then(data => 
                    {
                        // Test accounts bypass OTP and log in directly
                        if (data.status === 'ok') 
                        {
                            sessionStorage.removeItem('lockoutEndTime');
                            window.location.href = data.role === 'admin' 
                                ? '/admin/dashboard.php' 
                                : (getUrlParameter('redirect') || 'index.php');
                        }

                        else if (data.status === 'otp_required') 
                        {
                            fetch('/send_otp.php', { method: 'POST' })
                                .then(r => r.json())
                                .then(otpData => {
                                    if (otpData.status === 'ok') {
                                        disableForm(false);
                                        document.getElementById('login-form').style.display = 'none';
                                        document.getElementById('otp-form').style.display = 'block';
                                    } else {
                                        showError(otpData.error || 'Failed to send OTP.');
                                        disableForm(false);
                                    }
                                })
                                .catch(() => {
                                    showError('Failed to send OTP. Please try again.');
                                    disableForm(false);
                                });
                        }
                        
                        else if (data.locked) 
                        {
                            const lockoutEnd = Date.now() + (data.remaining_seconds * 1000);
                            sessionStorage.setItem('lockoutEndTime', lockoutEnd);
                            startCountdown(data.remaining_seconds);
                        } 
                        
                        else if (data.attempts_remaining) 
                        {
                            let warningMessage = `${data.error} You have ${data.attempts_remaining} attempt(s) remaining.`;
                            if (data.attempts_remaining === 1) 
                            {
                                warningMessage = `${data.error} Last attempt before 30-second lockout!`;
                            }
                            showError(warningMessage);
                            disableForm(false);
                        }
                        
                        else 
                        {
                            showError(data.error || 'Login failed.');
                            disableForm(false);
                        }
                    })
                    .catch(() => 
                    {
                        showError('An error occurred. Please try again.');
                        disableForm(false);
                    });
            });

            // Check for existing lockout when page loads
            document.addEventListener('DOMContentLoaded', function() {
                checkExistingLockout();
            });

            // Clear intervals when page unloads
            window.addEventListener('beforeunload', function() 
            {
                if (countdownInterval) {
                    clearInterval(countdownInterval);
                }
            });

            // Handle OTP submission
            document.getElementById('btn-verify-otp').addEventListener('click', function() 
            {
                const otp = document.getElementById('otp').value.trim();
                const btn = this;

                if (!otp || otp.length !== 6) {
                    showError('Please enter the 6-digit code.');
                    return;
                }

                btn.disabled = true;
                btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Verifying...';

                const body = new FormData();
                body.append('otp', otp);

                fetch('/verify_otp.php', { method: 'POST', body })
                    .then(r => r.json())
                    .then(data => 
                    {
                        if (data.status === 'ok') 
                        {
                            sessionStorage.removeItem('lockoutEndTime');
                            window.location.href = data.role === 'admin' 
                                ? '/admin/dashboard.php' 
                                : (getUrlParameter('redirect') || 'index.php');
                        } 
                        
                        else if (data.expired) 
                        {
                            // OTP expired - go back to login
                            showError(data.error);
                            document.getElementById('otp-form').style.display = 'none';
                            document.getElementById('login-form').style.display = 'block';
                            btn.disabled = false;
                            btn.innerHTML = 'Verify <i class="bi bi-arrow-right"></i>';
                        } 
                        
                        else 
                        {
                            showError(data.error || 'Invalid OTP.');
                            btn.disabled = false;
                            btn.innerHTML = 'Verify <i class="bi bi-arrow-right"></i>';
                        }
                    })
                    .catch(() => 
                    {
                        showError('An error occurred. Please try again.');
                        btn.disabled = false;
                        btn.innerHTML = 'Verify <i class="bi bi-arrow-right"></i>';
                    });
            });

            // Handles resend of OTP
            document.getElementById('resend-otp').addEventListener('click', function(e) 
            {
                e.preventDefault();
                this.textContent = 'Sending...';
                const link = this;

                fetch('/send_otp.php', { method: 'POST' })
                    .then(r => r.json())
                    .then(data => 
                    {
                        if (data.status === 'ok') 
                        {
                            showError('A new code has been sent to your email.');
                        } 
                        
                        else 
                        {
                            showError(data.error || 'Failed to resend OTP.');
                        }
                        link.textContent = 'Resend';
                    })
                    .catch(() => 
                    {
                        showError('Failed to resend. Please try again.');
                        link.textContent = 'Resend';
                    });
            });
        </script>
    </body>
</html>
