<?php

date_default_timezone_set('Africa/Cairo');
ini_set("precision", 3);

// Locations
const LOCAL = ".";
const LOG = LOCAL."/logs";

const DEBUG = false;

// Sqlite DB
define("DB_PATH", "./mydb.db");

const DEPENDENCIES = [
  // Global
  'DB' => 'database',

  // Libraries
  'Firebase\JWT\JWT' => "lib/php-jwt/src",
  'Firebase\JWT\Key' => "lib/php-jwt/src",

  // Helpers
  'Validator' => 'helpers/validation',
  'ValidationUnit' => 'helpers/validation',
  'Curl' => 'helpers',
  'Regex' => 'helpers',
  'Ndate' => 'helpers',
  'Entities' => 'helpers',
  'AssocEntities' => 'helpers',

  // Bases and Interfaces
  'Authenticated' => 'endpoints/bases',
  'Model' => 'models',
  'Mapper' => 'mappers',
  'JoinMapper' => 'mappers',
  'IModel' => 'models',
  'IMapper' => 'mappers',

  'Submission' => 'models/exams',

  // Exceptions
  'NegativeSectionReached' => 'exceptions',
  'InvalidArguments' => 'exceptions',

  'BadRequest' => 'exceptions',
  'Forbidden' => 'exceptions',
  'NotFound' => 'exceptions',
  'NotModified' => 'exceptions',
  'Old' => 'exceptions',
  'NotMatching' => 'exceptions',
  'Conflict' => 'exceptions',
  'Fail' => 'exceptions',

  'PropertyNotExisting' => 'models/exceptions',
  'RequiredPropertyNotFound' => 'models/exceptions',
  'IncompleteModel' => 'models/exceptions',
  'InvalidID' => 'models/exceptions',
  'IncompatibleModels' => 'models/exceptions',

  'UniquenessViolated' => 'database/exceptions',
  'ForeignKeyViolated' => 'database/exceptions',
];

// JWT Secret
const SECRET = 'lol';
