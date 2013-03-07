CREATE TABLE /*_*/currency_conversions (
  currency_code varchar(3) NOT NULL PRIMARY KEY,

  -- Number of times the question has been correctly answered
  exchange_rate float NOT NULL
) /*$wgDBTableOptions*/;