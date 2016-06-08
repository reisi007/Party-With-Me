SELECT
  u.id,
  u.firstName,
  u.lastName,
  floor(datediff(curdate(), u.birthday) / 365.24)                       AS age,
  u.interested IN (my.gender, 'b') AND my.interested IN (u.gender, 'b') AS interested
FROM ((SELECT userID
       FROM partywithme pwm
       WHERE eventId = :eventId) pwm, (SELECT
                                         id,
                                         interested,
                                         gender
                                       FROM users
                                       WHERE id = :userId) my) JOIN users u
    ON pwm.userID = u.id AND pwm.userID != my.id;