define({ "api": [
  {
    "type": "post",
    "url": "/v1/bookmarks",
    "title": "Create",
    "description": "<p>Creates a new bookmark.</p> ",
    "permission": [
      {
        "name": "write"
      }
    ],
    "group": "Bookmark",
    "name": "CreateBookmark",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "project_id",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "url",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "notes",
            "description": "<p>Related user written notes about this bookmark.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "title",
            "description": "<p>The contents of title in the page.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>String[]</p> ",
            "optional": true,
            "field": "tags",
            "description": "<p>A list of initial tags.</p> "
          }
        ]
      }
    },
    "version": "1.0.0",
    "filename": "./BookmarkController.php",
    "groupTitle": "Bookmark"
  },
  {
    "type": "delete",
    "url": "/v1/bookmarks/:id",
    "title": "Delete",
    "description": "<p>Deletes a single bookmark.</p> ",
    "permission": [
      {
        "name": "write"
      }
    ],
    "group": "Bookmark",
    "name": "DeleteBookmark",
    "version": "1.0.0",
    "filename": "./BookmarkController.php",
    "groupTitle": "Bookmark"
  },
  {
    "type": "get",
    "url": "/v1/bookmarks/:id",
    "title": "Get",
    "description": "<p>Gets a single bookmark.</p> ",
    "permission": [
      {
        "name": "read"
      }
    ],
    "group": "Bookmark",
    "name": "GetBookmark",
    "version": "1.0.0",
    "filename": "./BookmarkController.php",
    "groupTitle": "Bookmark"
  },
  {
    "type": "get",
    "url": "/v1/bookmarks",
    "title": "Get Multiple",
    "description": "<p>Gets a list of bookmarks. If the project_id is specified, returns all bookmarks in a project (not just owned by user). If project_id is omitted, then returns all user owned bookmarks.</p> ",
    "permission": [
      {
        "name": "read"
      }
    ],
    "group": "Bookmark",
    "name": "GetBookmarks",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": true,
            "field": "project_id",
            "description": ""
          }
        ]
      }
    },
    "version": "1.0.0",
    "filename": "./BookmarkController.php",
    "groupTitle": "Bookmark"
  },
  {
    "type": "put",
    "url": "/v1/bookmarks/:id/move",
    "title": "Move to Project",
    "description": "<p>Moves the bookmark to another project. Note: the user must have write permission on both 'from' and 'to' projects.</p> ",
    "permission": [
      {
        "name": "write"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "project_id",
            "description": "<p>The destination project.</p> "
          }
        ]
      }
    },
    "name": "MoveBookmark",
    "group": "Bookmark",
    "version": "1.0.0",
    "filename": "./BookmarkController.php",
    "groupTitle": "Bookmark"
  },
  {
    "type": "put",
    "url": "/v1/bookmarks/:id",
    "title": "Update",
    "description": "<p>Updates a bookmark.</p> ",
    "permission": [
      {
        "name": "write"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "url",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "title",
            "description": "<p>The contents of title in the page.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>String[]</p> ",
            "optional": true,
            "field": "tags",
            "description": "<p>A list of tags.</p> "
          }
        ]
      }
    },
    "group": "Bookmark",
    "name": "UpdateBookmark",
    "version": "1.0.0",
    "filename": "./BookmarkController.php",
    "groupTitle": "Bookmark"
  },
  {
    "type": "post",
    "url": "/v1/pages",
    "title": "Create",
    "description": "<p>Creates a new page.</p> ",
    "permission": [
      {
        "name": "write"
      }
    ],
    "group": "Page",
    "name": "CreatePage",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "project_id",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "url",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "title",
            "description": "<p>The contents of title in the page.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "if_query",
            "defaultValue": "both",
            "description": "<p>Used to determine the behavior when the url represents a search engine query page (e.g. https://www.google.com/search?q=test) This should be set to one of the following: 'page_only', 'query_only', or 'both'</p> "
          }
        ]
      }
    },
    "version": "1.0.0",
    "filename": "./PageController.php",
    "groupTitle": "Page"
  },
  {
    "type": "delete",
    "url": "/v1/pages/:id",
    "title": "Delete",
    "description": "<p>Deletes a single page.</p> ",
    "permission": [
      {
        "name": "write"
      }
    ],
    "group": "Page",
    "name": "DeletePage",
    "version": "1.0.0",
    "filename": "./PageController.php",
    "groupTitle": "Page"
  },
  {
    "type": "get",
    "url": "/v1/pages/:id",
    "title": "Get",
    "description": "<p>Gets a single page.</p> ",
    "permission": [
      {
        "name": "read"
      }
    ],
    "group": "Page",
    "name": "GetPage",
    "version": "1.0.0",
    "filename": "./PageController.php",
    "groupTitle": "Page"
  },
  {
    "type": "get",
    "url": "/v1/pages",
    "title": "Get Multiple",
    "description": "<p>Gets a list of pages. If the project_id is specified, returns all pages in a project (not just owned by user). If project_id is omitted, then returns all user owned pages.</p> ",
    "permission": [
      {
        "name": "read"
      }
    ],
    "group": "Page",
    "name": "GetPages",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": true,
            "field": "project_id",
            "description": ""
          }
        ]
      }
    },
    "version": "1.0.0",
    "filename": "./PageController.php",
    "groupTitle": "Page"
  },
  {
    "type": "post",
    "url": "/v1/projects/",
    "title": "Create",
    "description": "<p>Creates a single project and sets the user as owner.</p> ",
    "group": "Project",
    "name": "CreateProject",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "title",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "<p>Boolean</p> ",
            "optional": true,
            "field": "private",
            "defaultValue": "false",
            "description": "<p>Private projects are not publicly searchable.</p> "
          }
        ]
      }
    },
    "permission": [
      {
        "name": "write"
      }
    ],
    "version": "1.0.0",
    "filename": "./ProjectController.php",
    "groupTitle": "Project"
  },
  {
    "type": "delete",
    "url": "/v1/projects/:id",
    "title": "Delete",
    "description": "<p>Deletes a project if the user is the owner.</p> ",
    "group": "Project",
    "name": "DeleteProject",
    "permission": [
      {
        "name": "owner"
      }
    ],
    "version": "1.0.0",
    "filename": "./ProjectController.php",
    "groupTitle": "Project"
  },
  {
    "type": "delete",
    "url": "/v1/projects",
    "title": "Delete Projects",
    "description": "<p>Deletes multiple projects if the user is the owner.</p> ",
    "group": "Project",
    "name": "DeleteProjects",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>Integer[]</p> ",
            "optional": false,
            "field": "ids",
            "description": ""
          }
        ]
      }
    },
    "permission": [
      {
        "name": "owner"
      }
    ],
    "version": "1.0.0",
    "filename": "./ProjectController.php",
    "groupTitle": "Project"
  },
  {
    "type": "get",
    "url": "/v1/projects/:id",
    "title": "Get",
    "description": "<p>Returns a single project and the user's membership. If the project is public, the user does not need any permissions.</p> ",
    "group": "Project",
    "name": "GetProject",
    "permission": [
      {
        "name": "read"
      }
    ],
    "version": "1.0.0",
    "filename": "./ProjectController.php",
    "groupTitle": "Project"
  },
  {
    "type": "get",
    "url": "/v1/projects/:id/tags",
    "title": "Get Tags",
    "description": "<p>Get a list of all tags used in this project.</p> ",
    "group": "Project",
    "name": "GetProjectTags",
    "permission": [
      {
        "name": "read"
      }
    ],
    "version": "1.0.0",
    "filename": "./ProjectController.php",
    "groupTitle": "Project"
  },
  {
    "type": "get",
    "url": "/v1/projects",
    "title": "Get Multiple",
    "description": "<p>Returns a list of projects of which the user has membership.</p> ",
    "group": "Project",
    "name": "GetProjects",
    "permission": [
      {
        "name": "read"
      }
    ],
    "version": "1.0.0",
    "filename": "./ProjectController.php",
    "groupTitle": "Project"
  },
  {
    "type": "post",
    "url": "/v1/projects/:id/share",
    "title": "Share Project",
    "description": "<p>Share a project with another user.</p> ",
    "permission": [
      {
        "name": "own"
      }
    ],
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "user_id",
            "description": "<p>The id of the user (required if user_email is not present)</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "user_email",
            "description": "<p>The email of the user (required if user_id is not present)</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "permission",
            "description": "<p>Can be one of {w,r,o} representing write, read, and owner permissions.</p> "
          }
        ]
      }
    },
    "group": "Project",
    "name": "ShareProject",
    "version": "1.0.0",
    "filename": "./ProjectController.php",
    "groupTitle": "Project"
  },
  {
    "type": "put",
    "url": "/v1/projects/:id",
    "title": "Update",
    "description": "<p>Updates a project.</p> ",
    "group": "Project",
    "name": "UpdateProject",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "title",
            "description": ""
          }
        ]
      }
    },
    "permission": [
      {
        "name": "write"
      }
    ],
    "version": "1.0.0",
    "filename": "./ProjectController.php",
    "groupTitle": "Project"
  },
  {
    "type": "post",
    "url": "/v1/snippets",
    "title": "Create",
    "description": "<p>Creates a new snippet.</p> ",
    "permission": [
      {
        "name": "write"
      }
    ],
    "group": "Snippet",
    "name": "CreateSnippet",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "project_id",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "url",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "title",
            "description": "<p>The web page title.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "text",
            "description": "<p>The snippet contents.</p> "
          }
        ]
      }
    },
    "version": "1.0.0",
    "filename": "./SnippetController.php",
    "groupTitle": "Snippet"
  },
  {
    "type": "delete",
    "url": "/v1/snippets/:id",
    "title": "Delete",
    "description": "<p>Deletes a snippet</p> ",
    "permission": [
      {
        "name": "write"
      }
    ],
    "group": "Snippet",
    "name": "DeleteSnippet",
    "version": "1.0.0",
    "filename": "./SnippetController.php",
    "groupTitle": "Snippet"
  },
  {
    "type": "get",
    "url": "/v1/snippets/:id",
    "title": "Get",
    "description": "<p>Returns a single snippet</p> ",
    "permission": [
      {
        "name": "read"
      }
    ],
    "group": "Snippet",
    "name": "GetSnippet",
    "version": "1.0.0",
    "filename": "./SnippetController.php",
    "groupTitle": "Snippet"
  },
  {
    "type": "get",
    "url": "/v1/snippets",
    "title": "Get Multiple",
    "description": "<p>Returns multiple snippets</p> ",
    "permission": [
      {
        "name": "read"
      }
    ],
    "group": "Snippet",
    "name": "GetSnippets",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>integer</p> ",
            "optional": true,
            "field": "project_id",
            "description": ""
          }
        ]
      }
    },
    "version": "1.0.0",
    "filename": "./SnippetController.php",
    "groupTitle": "Snippet"
  },
  {
    "type": "put",
    "url": "/v1/snippets/:id",
    "title": "Update",
    "permission": [
      {
        "name": "write"
      }
    ],
    "group": "Snippet",
    "name": "UpdateSnippet",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "url",
            "description": ""
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "text",
            "description": ""
          }
        ]
      }
    },
    "version": "1.0.0",
    "filename": "./SnippetController.php",
    "groupTitle": "Snippet"
  },
  {
    "type": "post",
    "url": "/v1/user",
    "title": "Get",
    "description": "<p>Get the currently logged in user.</p> ",
    "group": "User",
    "name": "GetUser",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "auth_email",
            "description": "<p>The user email to authenticate.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": true,
            "field": "auth_password",
            "description": "<p>The user password to authenticate.</p> "
          }
        ]
      }
    },
    "version": "1.0.0",
    "filename": "./UserController.php",
    "groupTitle": "User"
  }
] });