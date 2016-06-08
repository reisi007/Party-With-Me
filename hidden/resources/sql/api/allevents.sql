SELECT
  count(pwm.userID) - self_going AS 'pwm_requests',
  d.*
FROM (SELECT
        !isnull(pwm.userID) AS 'self_going',
        e.*
      FROM (SELECT eventID
            FROM visits v
            WHERE userID = :userId) v
        JOIN events e ON v.eventID = e.id AND e.endTime > current_timestamp
        LEFT JOIN partywithme pwm ON v.eventID = pwm.eventID AND :userId = pwm.userID) d LEFT JOIN partywithme pwm
    ON d.id = pwm.eventID
GROUP BY d.id
ORDER BY d.startTime ASC, d.endTime DESC