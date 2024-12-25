-- User Roles Table
CREATE TABLE Roles (
    RoleID INT IDENTITY(1,1) PRIMARY KEY,
    RoleName VARCHAR(50) NOT NULL UNIQUE -- e.g., 'Creator', 'Consumer'
);

-- Users Table
CREATE TABLE Users (
    UserID INT IDENTITY(1,1) PRIMARY KEY,
    Username VARCHAR(50) NOT NULL UNIQUE,
    Email VARCHAR(100) NOT NULL UNIQUE,
    PasswordHash VARCHAR(255) NOT NULL, -- Store hashed passwords
    RoleID INT NOT NULL,
    CreatedAt DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (RoleID) REFERENCES Roles(RoleID)
);

-- User Authentication Tokens Table
CREATE TABLE AuthenticationTokens (
    TokenID INT IDENTITY(1,1) PRIMARY KEY,
    UserID INT NOT NULL,
    Token VARCHAR(255) NOT NULL, -- Stores the token (JWT or session-based)
    Expiry DATETIME NOT NULL,
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Videos Table
CREATE TABLE Videos (
    VideoID INT IDENTITY(1,1) PRIMARY KEY,
    Title VARCHAR(100) NOT NULL,
    Description TEXT,
    URL VARCHAR(255) NOT NULL, -- Video storage URL (e.g., S3, Cloud Storage)
    ThumbnailURL VARCHAR(255), -- Thumbnail image URL
    CreatorID INT NOT NULL, -- Link to Users table (Creator)
    CreatedAt DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (CreatorID) REFERENCES Users(UserID)
);

-- Tags Table (for hashtags)
CREATE TABLE Tags (
    TagID INT IDENTITY(1,1) PRIMARY KEY,
    TagName VARCHAR(50) NOT NULL UNIQUE
);

-- Video Tags (Many-to-Many relationship)
CREATE TABLE VideoTags (
    VideoID INT NOT NULL,
    TagID INT NOT NULL,
    PRIMARY KEY (VideoID, TagID),
    FOREIGN KEY (VideoID) REFERENCES Videos(VideoID),
    FOREIGN KEY (TagID) REFERENCES Tags(TagID)
);

-- Comments Table
CREATE TABLE Comments (
    CommentID INT IDENTITY(1,1) PRIMARY KEY,
    VideoID INT NOT NULL,
    UserID INT NOT NULL,
    Content TEXT NOT NULL,
    CreatedAt DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (VideoID) REFERENCES Videos(VideoID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Ratings Table
CREATE TABLE Ratings (
    RatingID INT IDENTITY(1,1) PRIMARY KEY,
    VideoID INT NOT NULL,
    UserID INT NOT NULL,
    Rating INT CHECK (Rating BETWEEN 1 AND 5), -- 1 to 5 star ratings
    CreatedAt DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (VideoID) REFERENCES Videos(VideoID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID),
    CONSTRAINT UQ_VideoUserRating UNIQUE (VideoID, UserID) -- A user can rate a video only once
);

-- Video Views (Tracking views)
CREATE TABLE VideoViews (
    ViewID INT IDENTITY(1,1) PRIMARY KEY,
    VideoID INT NOT NULL,
    UserID INT, -- Null for anonymous viewers
    ViewedAt DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (VideoID) REFERENCES Videos(VideoID),
    FOREIGN KEY (UserID) REFERENCES Users(UserID)
);

-- Video Engagement (Views, Likes, Shares)
CREATE TABLE VideoEngagement (
    EngagementID INT IDENTITY(1,1) PRIMARY KEY,
    VideoID INT NOT NULL,
    Views INT DEFAULT 0,
    Likes INT DEFAULT 0,
    Shares INT DEFAULT 0,
    FOREIGN KEY (VideoID) REFERENCES Videos(VideoID)
);

-- Cache for popular or trending videos
CREATE TABLE VideoCache (
    VideoID INT NOT NULL,
    CachedAt DATETIME DEFAULT GETDATE(),
    PRIMARY KEY (VideoID)
);
