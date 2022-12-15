#置換前と置換後の文字列を対話モードで受け付ける
echo "Input old text"
read oldText
echo "Input new text"
read newText


#フォルダ名、ファイル名を置換する
for i in $(seq $(find . -type d | wc -l))
do
    find . -maxdepth $i -name '*'$oldText'*' | \
    while read line
    do newline=$(echo $line | sed 's/'$oldText'/'$newText'/g')
        echo $newline
        mv "$line" $newline
    done
done

#ファイル内に含まれる文字列を置換する
# find ./ -name '*.js' -exec sed -i '' 's/'$oldText'/'$newText'/g' {} \;