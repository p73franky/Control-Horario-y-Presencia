const sqlite3 = require('sqlite3').verbose();

const db = new sqlite3.Database('./fichajes.db');

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
