<?php
// Modif 5
require_once("../../github_key.php");
$now = new \DateTime();
echo $now->format('d/m/Y h:i:s')."\n\n";
$post_data = file_get_contents('php://input');
$signature = 'sha1='.hash_hmac('sha1', $post_data, GIT_HUB_KEY);
if($signature == $_SERVER[ 'HTTP_X_HUB_SIGNATURE' ]){
    // TODO : exécution du script sh s si en branche dév && mode && projet changesous
}
/*
 {
  "ref": "refs/heads/dev",
  "before": "0b20b2e1e2a354266c54ad84379981f2c06671b1",
  "after": "2f08d9e4e8782e30108dfddf3062c862bd3a726f",
  "created": false,
  "deleted": false,
  "forced": false,
  "base_ref": null,
  "compare": "https://github.com/jcognet/changesous/compare/0b20b2e1e2a3...2f08d9e4e878",
  "commits": [
    {
      "id": "2f08d9e4e8782e30108dfddf3062c862bd3a726f",
      "tree_id": "23793e86f88b2fc488e1947cbae58c2265778c43",
      "distinct": true,
      "message": "push",
      "timestamp": "2016-11-26T15:05:19+01:00",
      "url": "https://github.com/jcognet/changesous/commit/2f08d9e4e8782e30108dfddf3062c862bd3a726f",
      "author": {
        "name": "Jerome cognet",
        "email": "jcognet@gmail.com",
        "username": "jcognet"
      },
      "committer": {
        "name": "Jerome cognet",
        "email": "jcognet@gmail.com",
        "username": "jcognet"
      },
      "added": [

      ],
      "removed": [

      ],
      "modified": [
        "web/push.php"
      ]
    }
  ],
  "head_commit": {
    "id": "2f08d9e4e8782e30108dfddf3062c862bd3a726f",
    "tree_id": "23793e86f88b2fc488e1947cbae58c2265778c43",
    "distinct": true,
    "message": "push",
    "timestamp": "2016-11-26T15:05:19+01:00",
    "url": "https://github.com/jcognet/changesous/commit/2f08d9e4e8782e30108dfddf3062c862bd3a726f",
    "author": {
      "name": "Jerome cognet",
      "email": "jcognet@gmail.com",
      "username": "jcognet"
    },
    "committer": {
      "name": "Jerome cognet",
      "email": "jcognet@gmail.com",
      "username": "jcognet"
    },
    "added": [

    ],
    "removed": [

    ],
    "modified": [
      "web/push.php"
    ]
  },
  "repository": {
    "id": 71762102,
    "name": "changesous",
    "full_name": "jcognet/changesous",
    "owner": {
      "name": "jcognet",
      "email": "jcognet@gmail.com"
    },
    "private": false,
    "html_url": "https://github.com/jcognet/changesous",
    "description": "Site qui suit les devises, bac à sable de Jérôme COGNET",
    "fork": false,
    "url": "https://github.com/jcognet/changesous",
    "forks_url": "https://api.github.com/repos/jcognet/changesous/forks",
    "keys_url": "https://api.github.com/repos/jcognet/changesous/keys{/key_id}",
    "collaborators_url": "https://api.github.com/repos/jcognet/changesous/collaborators{/collaborator}",
    "teams_url": "https://api.github.com/repos/jcognet/changesous/teams",
    "hooks_url": "https://api.github.com/repos/jcognet/changesous/hooks",
    "issue_events_url": "https://api.github.com/repos/jcognet/changesous/issues/events{/number}",
    "events_url": "https://api.github.com/repos/jcognet/changesous/events",
    "assignees_url": "https://api.github.com/repos/jcognet/changesous/assignees{/user}",
    "branches_url": "https://api.github.com/repos/jcognet/changesous/branches{/branch}",
    "tags_url": "https://api.github.com/repos/jcognet/changesous/tags",
    "blobs_url": "https://api.github.com/repos/jcognet/changesous/git/blobs{/sha}",
    "git_tags_url": "https://api.github.com/repos/jcognet/changesous/git/tags{/sha}",
    "git_refs_url": "https://api.github.com/repos/jcognet/changesous/git/refs{/sha}",
    "trees_url": "https://api.github.com/repos/jcognet/changesous/git/trees{/sha}",
    "statuses_url": "https://api.github.com/repos/jcognet/changesous/statuses/{sha}",
    "languages_url": "https://api.github.com/repos/jcognet/changesous/languages",
    "stargazers_url": "https://api.github.com/repos/jcognet/changesous/stargazers",
    "contributors_url": "https://api.github.com/repos/jcognet/changesous/contributors",
    "subscribers_url": "https://api.github.com/repos/jcognet/changesous/subscribers",
    "subscription_url": "https://api.github.com/repos/jcognet/changesous/subscription",
    "commits_url": "https://api.github.com/repos/jcognet/changesous/commits{/sha}",
    "git_commits_url": "https://api.github.com/repos/jcognet/changesous/git/commits{/sha}",
    "comments_url": "https://api.github.com/repos/jcognet/changesous/comments{/number}",
    "issue_comment_url": "https://api.github.com/repos/jcognet/changesous/issues/comments{/number}",
    "contents_url": "https://api.github.com/repos/jcognet/changesous/contents/{+path}",
    "compare_url": "https://api.github.com/repos/jcognet/changesous/compare/{base}...{head}",
    "merges_url": "https://api.github.com/repos/jcognet/changesous/merges",
    "archive_url": "https://api.github.com/repos/jcognet/changesous/{archive_format}{/ref}",
    "downloads_url": "https://api.github.com/repos/jcognet/changesous/downloads",
    "issues_url": "https://api.github.com/repos/jcognet/changesous/issues{/number}",
    "pulls_url": "https://api.github.com/repos/jcognet/changesous/pulls{/number}",
    "milestones_url": "https://api.github.com/repos/jcognet/changesous/milestones{/number}",
    "notifications_url": "https://api.github.com/repos/jcognet/changesous/notifications{?since,all,participating}",
    "labels_url": "https://api.github.com/repos/jcognet/changesous/labels{/name}",
    "releases_url": "https://api.github.com/repos/jcognet/changesous/releases{/id}",
    "deployments_url": "https://api.github.com/repos/jcognet/changesous/deployments",
    "created_at": 1477293363,
    "updated_at": "2016-11-25T21:12:51Z",
    "pushed_at": 1480169106,
    "git_url": "git://github.com/jcognet/changesous.git",
    "ssh_url": "git@github.com:jcognet/changesous.git",
    "clone_url": "https://github.com/jcognet/changesous.git",
    "svn_url": "https://github.com/jcognet/changesous",
    "homepage": "http://www.changesous.com",
    "size": 410,
    "stargazers_count": 0,
    "watchers_count": 0,
    "language": "PHP",
    "has_issues": true,
    "has_downloads": true,
    "has_wiki": true,
    "has_pages": false,
    "forks_count": 0,
    "mirror_url": null,
    "open_issues_count": 1,
    "forks": 0,
    "open_issues": 1,
    "watchers": 0,
    "default_branch": "master",
    "stargazers": 0,
    "master_branch": "master"
  },
  "pusher": {
    "name": "jcognet",
    "email": "jcognet@gmail.com"
  },
  "sender": {
    "login": "jcognet",
    "id": 22724959,
    "avatar_url": "https://avatars.githubusercontent.com/u/22724959?v=3",
    "gravatar_id": "",
    "url": "https://api.github.com/users/jcognet",
    "html_url": "https://github.com/jcognet",
    "followers_url": "https://api.github.com/users/jcognet/followers",
    "following_url": "https://api.github.com/users/jcognet/following{/other_user}",
    "gists_url": "https://api.github.com/users/jcognet/gists{/gist_id}",
    "starred_url": "https://api.github.com/users/jcognet/starred{/owner}{/repo}",
    "subscriptions_url": "https://api.github.com/users/jcognet/subscriptions",
    "organizations_url": "https://api.github.com/users/jcognet/orgs",
    "repos_url": "https://api.github.com/users/jcognet/repos",
    "events_url": "https://api.github.com/users/jcognet/events{/privacy}",
    "received_events_url": "https://api.github.com/users/jcognet/received_events",
    "type": "User",
    "site_admin": false
  }
}
 */
?>