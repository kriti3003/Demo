const express = require('express');
const bcrypt = require('bcrypt');
const bodyParser = require('body-parser');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static('public'));

const users = []; // Simulated user database (in-memory array)

// Serve the login form
app.get('/login', (req, res) => {
  res.sendFile(__dirname + '/public/login.html');
});

// Handle login POST request
app.post('/login', async (req, res) => {
  const { username, password } = req.body;

  // Check if the user exists
  const user = users.find((user) => user.username === username);

  if (user && await bcrypt.compare(password, user.passwordHash)) {
    res.send('Login successful!');
  } else {
    res.send('Invalid username or password.');
  }
});

// Serve the registration form
app.get('/register', (req, res) => {
  res.sendFile(__dirname + '/public/register.html');
});

// Handle registration POST request
app.post('/register', async (req, res) => {
  const { username, password } = req.body;

  // Check if the user already exists
  if (users.some((user) => user.username === username)) {
    res.send('Username already exists. Please choose another one.');
    return;
  }

  // Hash the password before storing it
  const saltRounds = 10;
  const passwordHash = await bcrypt.hash(password, saltRounds);

  // Store the user in the simulated database
  users.push({
    username,
    passwordHash,
  });

  res.send('Registration successful!');
});

app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
