CREATE TABLE IF NOT EXISTS users (
  id         BIGINT UNSIGNED PRIMARY KEY,
  firstName  TEXT                      NOT NULL,
  lastName   TEXT                      NOT NULL,
  gender     ENUM ('m', 'f')           NOT NULL,
  interested ENUM ('m', 'f', 'b', 'n') NOT NULL,
  birthday   DATE                      NOT NULL
);
CREATE TABLE IF NOT EXISTS events (
  id          BIGINT UNSIGNED PRIMARY KEY,
  name        TEXT      NOT NULL,
  description TEXT      NOT NULL,
  coverUrl    TEXT      NOT NULL,
  place       TEXT      NOT NULL,
  startTime   TIMESTAMP NOT NULL,
  endTime     TIMESTAMP NOT NULL
);
CREATE TABLE IF NOT EXISTS visits (
  userID      BIGINT UNSIGNED,
  eventID     BIGINT UNSIGNED,
  rsvp_status ENUM ('unsure', 'attending') NOT NULL,
  UNIQUE (userID, eventID),
  INDEX (userID),
  INDEX (eventID),
  FOREIGN KEY (userID) REFERENCES users (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  FOREIGN KEY (eventID) REFERENCES events (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
CREATE TABLE IF NOT EXISTS partywithme (
  userID  BIGINT UNSIGNED,
  eventID BIGINT UNSIGNED,
  INDEX (eventID),
  UNIQUE (userID, eventID),
  FOREIGN KEY (userID) REFERENCES visits (userID)
    ON DELETE CASCADE,
  FOREIGN KEY (eventID) REFERENCES events (id)
    ON DELETE CASCADE
);
COMMIT;