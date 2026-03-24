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
                        <label for="username" class="form-label">Username:</label>
                        <div class="input-wrap">
                            <i class="bi bi-person"></i>
                            <input type="text" name="username" id="username" class="form-control" required>
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

                <p class="register-prompt">Don't have an account? <a href="register.php">Register here</a></p>
            </div>
        </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  <script>
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
        const usernameInput = document.getElementById('username');
        const passwordInput = document.getElementById('password');
        const loginBtn = document.getElementById('btn-login');
        
        usernameInput.disabled = disabled;
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

    // Function to start countdown from remaining seconds
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

    // Check for existing lockout on page load
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


    document.getElementById('btn-login').addEventListener('click', function () 
    {
        // Don't process if already locked out
        if (isLockedOut || countdownInterval) 
        {
            console.log('Currently locked out, please wait');
            return;
        }
        
        const username = document.getElementById('username').value.trim();
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
        
        const body = new FormData();
        body.append('username', username);
        body.append('password', password);
        
        fetch('/api/login.php', { method: 'POST', body })
            .then(r => r.json())
            .then(data => 
            {
                if (data.status === 'ok') 
                {
                    // Success - clear any stored lockout data
                    sessionStorage.removeItem('lockoutEndTime');
                    
                    // Redirect
                    if (data.role === 'admin') 
                    {
                        window.location.href = '/admin/dashboard.php';
                    } 
                    
                    else 
                    {
                        window.location.href = redirect;
                    }
                } 
                
                else if (data.locked) 
                {
                    // Account is locked - start countdown
                    const lockoutEnd = Date.now() + (data.remaining_seconds * 1000);
                    sessionStorage.setItem('lockoutEndTime', lockoutEnd);
                    startCountdown(data.remaining_seconds);
                } 
                
                else if (data.attempts_remaining) 
                {
                    // Show remaining attempts
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
                    // Regular error
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
    window.addEventListener('beforeunload', function() {
        if (countdownInterval) {
            clearInterval(countdownInterval);
        }
    });
  </script>
</body>
</html>
