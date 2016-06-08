SELECT
  id,
  name,
  description,
  coverUrl,
  place,
  startTime,
  rsvp_status,
  partywithme
FROM (SELECT
        v.eventId,
        v.rsvp_status,
        NOT isnull(pwm.eventID) AS partywithme
      FROM (SELECT *
            FROM visits
            WHERE eventID = :eventId AND userID = :userId) v LEFT JOIN partywithme pwm
          ON v.userId = pwm.userId AND pwm.eventID = v.eventId) i LEFT JOIN events e ON i.eventId = e.id;