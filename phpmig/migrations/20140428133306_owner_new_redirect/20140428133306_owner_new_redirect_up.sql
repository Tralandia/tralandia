-- OwnerNewRedirect migration UP file

update user_role set homePage = ":Owner:Dashboard:default" where slug = "owner";
