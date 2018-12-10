/* INSERTCATEGORY : iNSERT OLD CATEGORYIES INTO NEW TABLE*/

DELIMITER $$
CREATE DEFINER=`root`@`%` PROCEDURE `insertCategory`()
    NO SQL
BEGIN
DECLARE pl_done INT DEFAULT 0;
DECLARE pl_name CHARACTER(255);
DECLARE pl_id,pl_parent_id,pl_user_id,pl_valid,pl_channel_id INT;
DECLARE pl_new_id,pl_old_id,pl_new_level,pl_new_channel_id INT;

DECLARE pl_cat1 CURSOR FOR SELECT category_id,name,user_id,valid,channel_id from category;

DECLARE pl_cat2 CURSOR FOR SELECT category_two_id,name,user_id,valid,category_id from category_two;

DECLARE pl_cat3 CURSOR FOR SELECT category_three_id,name,user_id,valid,category_two_id from category_three;

DECLARE pl_cat4 CURSOR FOR SELECT category_four_id,name,user_id,valid,category_three_id from category_four;

DECLARE pl_cat_new CURSOR FOR SELECT id,old_id,level,channel_id from category_temp where level<=3 order by level;

DECLARE CONTINUE HANDLER FOR NOT FOUND SET pl_done = 1;

OPEN pl_cat1;
	pl_insert_cat1: LOOP
		FETCH pl_cat1 INTO pl_id,pl_name,pl_user_id,pl_valid,pl_channel_id;
		IF pl_done THEN
			LEAVE pl_insert_cat1;
		END IF;
		insert into category_temp set name=pl_name,user_id=pl_user_id,channel_id=pl_channel_id,valid=pl_valid,old_id=pl_id,level=1,created_at=now(),updated_at=now();
	END LOOP;
CLOSE pl_cat1;

SET pl_done=0;

OPEN pl_cat2;
	pl_insert_cat2: LOOP
		FETCH pl_cat2 INTO pl_id,pl_name,pl_user_id,pl_valid,pl_parent_id;
		IF pl_done THEN
			LEAVE pl_insert_cat2;
		END IF;
		insert into category_temp set name=pl_name,user_id=pl_user_id,valid=pl_valid,old_id=pl_id,old_parent_id=pl_parent_id,level=2,created_at=now(),updated_at=now();

	END LOOP;
CLOSE pl_cat2;

SET pl_done=0;

OPEN pl_cat3;
	pl_insert_cat3: LOOP
		FETCH pl_cat3 INTO pl_id,pl_name,pl_user_id,pl_valid,pl_parent_id;
		IF pl_done THEN
			LEAVE pl_insert_cat3;
		END IF;
		insert into category_temp set name=pl_name,user_id=pl_user_id,valid=pl_valid,old_id=pl_id,old_parent_id=pl_parent_id,level=3,created_at=now(),updated_at=now();

	END LOOP;
CLOSE pl_cat3;

SET pl_done=0;

OPEN pl_cat4;
	pl_insert_cat4: LOOP
		FETCH pl_cat4 INTO pl_id,pl_name,pl_user_id,pl_valid,pl_parent_id;
		IF pl_done THEN
			LEAVE pl_insert_cat4;
		END IF;
		insert into category_temp set name=pl_name,user_id=pl_user_id,valid=pl_valid,old_id=pl_id,old_parent_id=pl_parent_id,level=4,created_at=now(),updated_at=now();

	END LOOP;
CLOSE pl_cat4;


SET pl_done=0;

OPEN pl_cat_new;
	pl_update_parent: LOOP
		FETCH pl_cat_new into pl_new_id,pl_old_id,pl_new_level,pl_new_channel_id;
		IF pl_done THEN
			LEAVE pl_update_parent;
		END IF;
		SET pl_new_level=pl_new_level+1;
		update category_temp set parent_id=pl_new_id,channel_id=pl_new_channel_id where old_parent_id=pl_old_id and level=pl_new_level;	

	END LOOP;
CLOSE pl_cat_new;	

END$$
DELIMITER ;
