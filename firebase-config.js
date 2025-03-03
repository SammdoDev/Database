// Konfigurasi Firebase
const firebaseConfig = {
  apiKey: "AIzaSyBwgMngpadv--rw4PbDHVV5yeU-1iXGik0",
  authDomain: "belajar-database-366ba.firebaseapp.com",
  databaseURL: "https://belajar-database-366ba-default-rtdb.asia-southeast1.firebasedatabase.app",
  projectId: "belajar-database-366ba",
  storageBucket: "belajar-database-366ba.appspot.com",
  messagingSenderId: "539720423013",
  appId: "1:539720423013:web:2226e92fdc6533a3d5988c",
  measurementId: "G-WJN5NN459D",
};

// Inisialisasi Firebase
firebase.initializeApp(firebaseConfig);

// Definisikan db secara global
const db = firebase.database();
