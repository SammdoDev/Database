<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://www.gstatic.com/firebasejs/11.4.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/11.4.0/firebase-auth-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/11.4.0/firebase-database-compat.js"></script>
    <script src="./firebase-config.js"></script>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold text-center mb-4">Login</h2>
        <p id="error" class="text-red-500 text-center mb-4"></p>
        <form id="loginForm" class="space-y-4">
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700">Role:</label>
                <select id="role" name="role" required class="w-full p-2 border border-gray-300 rounded-lg">
                    <option value="Saidan">Saidan</option>
                    <option value="Solo">Solo</option>
                    <option value="Sora">Sora</option>
                    <option value="Grand Edge">Grand Edge</option>
                    <option value="Soal Rambut">Soal Rambut</option>
                </select>
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password:</label>
                <input type="password" id="password" name="password" required class="w-full p-2 border border-gray-300 rounded-lg">
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-lg hover:bg-blue-600 transition">Login</button>
        </form>
    </div>

    <script>
        document.getElementById("loginForm").addEventListener("submit", function(event) {
            event.preventDefault();
            const role = document.getElementById("role").value;
            const password = document.getElementById("password").value;

            firebase.database().ref("users").orderByChild("role").equalTo(role).once("value")
                .then(snapshot => {
                    let userFound = false;
                    snapshot.forEach(childSnapshot => {
                        const userData = childSnapshot.val();
                        if (userData.password === password) {
                            sessionStorage.setItem("role", role);
                            
                            // Redirect sesuai role
                            let redirectPage = "dashboard.php";
                            switch (role) {
                                case "Saidan":
                                    redirectPage = "dashboard/saidan_dashboard.php";
                                    break;
                                case "Solo":
                                    redirectPage = "dashboard/solo_dashboard.php";
                                    break;
                                case "Sora":
                                    redirectPage = "dashboard/sora_dashboard.php";
                                    break;
                                case "Grand Edge":
                                    redirectPage = "dashboard/grandedge_dashboard.php";
                                    break;
                                case "Soal Rambut":
                                    redirectPage = "dashboard/soalrambut_dashboard.php";
                                    break;
                            }
                            
                            window.location.href = redirectPage;
                            userFound = true;
                        }
                    });
                    if (!userFound) {
                        document.getElementById("error").textContent = "Login gagal: Role atau password salah.";
                    }
                })
                .catch(error => {
                    document.getElementById("error").textContent = "Terjadi kesalahan: " + error.message;
                });
        });
    </script>
</body>
</html>
