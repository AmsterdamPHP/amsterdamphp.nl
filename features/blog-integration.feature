Feature:
  In order to see what happens on the blog
  As a Visitor
  I need to see the two latest blog posts on the homepage

  Scenario: Two blog posts show up on homepage
    Given There are "two" "posts" in the blog
     When I go to "/"
     Then I should see "2" ".blog-post" elements

  Scenario: New blog posts replace previous ones
    Given There are "two" "posts" in the blog
     When A new post is added to the blog
      And I go to "/"
     Then I should see "new title"
      And I should see "sample title 2"
      And I should not see "sample title 1"
