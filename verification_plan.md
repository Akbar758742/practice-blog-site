
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

## 4. Page Management
- [ ] Login as Admin
- [ ] Navigate to 'Pages' in Sidebar
- [ ] Create a new page (Title, Slug, Content, Visibility, SEO)
- [ ] Edit existing page
- [ ] Delete page
- [ ] Verify non-admin users cannot access Page Management
