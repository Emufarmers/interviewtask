CREATE TABLE currency_conversions (
  -- The three-letter identifier for a foreign currency
  currency_code varchar(3) NOT NULL PRIMARY KEY,
  
  -- The exchange rate of the currency (vs. USD)
  exchange_rate float NOT NULL
)
