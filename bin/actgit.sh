git fetch origin
git merge origin/master
rama=`git branch | grep "^*"`
if (test "$rama" = "* master") then {
    echo "rama=$rama"
    git fetch upstream
    git merge upstream/master
} fi;
