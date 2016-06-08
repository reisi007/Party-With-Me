INSERT INTO users (id, firstName, lastName, gender, interested, birthday)
VALUES (:id, :firstName, :lastName, :gender, :interested, STR_TO_DATE(:birthday, '%m/%d/%Y'))
ON DUPLICATE KEY UPDATE firstName = :firstName, lastName = :lastName, gender = :gender, interested = :interested,
  birthday                        = STR_TO_DATE(:birthday, '%m/%d/%Y')

