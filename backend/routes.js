const express = require('express');
const router = express.Router();
const db = require('./database');

router.post('/fichar', (req, res) => {
  const { tipo, fecha, hora, lat, lng } = req.body;

  db.run(
    `INSERT INTO fichajes (tipo, fecha, hora, lat, lng)
     VALUES (?, ?, ?, ?, ?)`,
    [tipo, fecha, hora, lat, lng],
    () => res.json({ ok: true })
  );
});

router.get('/fichajes', (req, res) => {
  db.all(`SELECT * FROM fichajes`, (err, rows) => {
    res.json(rows);
  });
});

module.exports = router;
