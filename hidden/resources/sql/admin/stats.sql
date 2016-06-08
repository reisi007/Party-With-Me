SELECT
  (SELECT count(*)
   FROM users)       AS 'Users',
  (SELECT count(*)
   FROM events)      AS 'Events',
  (SELECT count(*)
   FROM partywithme) AS 'Party with me requests',
  (SELECT count(*)
   FROM visits)      AS 'Currently registered event visits'