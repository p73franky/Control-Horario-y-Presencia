const sqlite3 = require('sqlite3').verbose();
const path = require('path');

const dbPath = path.join(__dirname, 'fichajes.db');


const db = new sqlite3.Database(dbPath, (err) => {
  if (err) {
    console.error('Error al conectar con la base de datos', err);
  } else {
    console.log('Base de datos conectada');
  }
});


db.serialize(() => {
  db.run(`
    CREATE TABLE IF NOT EXISTS fichajes (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      tipo TEXT,
      fecha TEXT,
      hora TEXT,
      lat REAL,
      lng REAL
    )
  `);
});

module.exports = db;
