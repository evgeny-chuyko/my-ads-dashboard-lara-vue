-- PostgreSQL initialization script
-- This script runs only on first container startup

-- Create extensions if needed
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- Set timezone
SET timezone = 'UTC';

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE myads_dashboard TO myads_user;
