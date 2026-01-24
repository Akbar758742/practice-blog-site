
# Verification Plan

## 1. Content Ownership & Policies
- [ ] Login as 'Author'
    - [ ] Create a post (should succeed)
    - [ ] Edit own post (should succeed)
    - [ ] Edit another user's post (should fail/hide)
    - [ ] Delete valid post (should fail - Authors can't delete)
    - [ ] Publish post (should fail - Authors can only set to Draft/Pending, enforced by UI fallback and backend check)
- [ ] Login as 'Editor'
    - [ ] Edit any post (should succeed)
    - [ ] Publish any post (should succeed)
    - [ ] Delete any post (should fail - Editors can't delete)
- [ ] Login as 'Admin'
    - [ ] Do everything (Create, Edit, Delete, Publish)

## 2. Role-Aware Content Workflow
- [ ] Check Post status dropdown in Create/Edit Post
    - [ ] Statuses: Draft, Pending, Published, Archived
- [ ] Verify Author sees 'Draft', 'Pending' (if they try 'Published', system should revert to 'Pending' or error)
- [ ] Verify Admin/Editor can set 'Published'

## 3. Comment Moderation
- [ ] Navigate to 'Comments' in Sidebar (Admin/Editor/Moderator only)
- [ ] View list of comments
- [ ] Approve a pending comment
- [ ] Mark a comment as Spam
- [ ] Delete a comment
- [ ] Verify functionality of 'Comments Allowed' toggle on Post Create/Edit

## 4. Frontend Comments
- [ ] **Submit Comment**:
    - [ ] Login as any user.
    - [ ] Go to any single post page.
    - [ ] Submit a comment.
    - [ ] Verify "Comment submitted" message.
- [ ] **Guest Access**:
    - [ ] Logout and verify "Name" and "Email" fields appear.
    - [ ] Submit as guest.
- [ ] **Nested Replies**:
    - [ ] Click reply on a comment, verify nesting.

## 5. Dashboard Summaries (NEW)
- [ ] **Login as Admin**:
- [ ] **Dashboard Widgets**:
    - [ ] Verify "Total Comments" widget.
    - [ ] Verify "Posts by Role" (Admins vs Authors) progress bar/stats.
    - [ ] Verify "Pending Comments" count.
- [ ] **Posts List**:
    - [ ] Go to Posts -> All Posts.
    - [ ] Verify "Comments" column shows the number of comments for each post.
